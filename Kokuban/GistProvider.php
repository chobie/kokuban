<?php
namespace Kokuban;

class GistProvider implements \Silex\ControllerProviderInterface
{
	public function initialize(\Silex\Application $app)
	{
		return $app;
	}

	public function connect(\Silex\Application $app)
	{
		$app = $this->initialize($app);
		$collection = new \Silex\ControllerCollection();

		$collection->get("/", function(\Silex\Application $app){
			$tmp = $app['redis']->lrange('kokuban.list',0,10);
			$list = array();

			foreach($tmp as $offset => $value) {
			  $list[$value] = unserialize($app['redis']->get($value));
			}

			return new \Symfony\Component\HttpFoundation\Response($app['twig']->render('index.htm',array('list'=>$list,'msg'=>"")));
		});

		$collection->post("/new", function(\Silex\Application $app){
				$description = $app['request']->get('description');
				$name = $app['request']->get('name');
				$contents = $app['request']->get('contents');

				$seed =  strtr(microtime(true),array("."=>""));
				$repo = \Git2\Repository::init(\REPOSITORY_DIRS . $seed . ".git");
				$oid = $repo->write($contents, 3);

				$builder = new \Git2\TreeBuilder();
				$entry = new \Git2\TreeEntry(array(
						"name" => $name,
						"oid"  => $oid,
						"attributes" => octdec('100644'),
						));
				$builder->insert($entry);
				$toid = $builder->write($repo);


				$sig = new \Git2\Signature("Jon Doe","example@example.com",new \DateTime());

				$parents = array();
				$coid = \Git2\Commit::create($repo,array(
							"author"    => $sig,
							"committer" => $sig,
							"message"   => $description,
							"tree"      => $toid,
							"parents"   => $parents
				));
				$entity = new \Kokuban\Entity($seed);
				$app['redis']->set($seed,serialize($entity));
				$app['redis']->lpush('kokuban.list',$seed);
				$msg =  "{$seed}.git was successfully created";

				return new \Symfony\Component\HttpFoundation\Response($app['twig']->render('index.htm',array('list'=>$list,"msg"=>$msg)));
		});

		$collection->get("/{id}",function(\Silex\Application $app){
			$repo = new \Git2\Repository(\REPOSITORY_DIRS . $app['request']->get('id').".git");
			$head = \Git2\Reference::lookup($repo,"refs/heads/master");
			$head = $head->resolve();
			$walker = new \Git2\Walker($repo);
			$walker->push($head->getTarget());
			$revs = array();
			$i = 0;
			foreach($walker as $entry){
			  if($i>20) break;
			  $revs[] = $entry;
			  if($i==0) $h = $entry;
			  $i++;
			}
			$tmp_tree = $h->getTree();
			$tree = array();
			foreach($tmp_tree as $t){
			  $tree[] = array(
				"entry" => $t,
				"object" => $repo->lookup($t->oid),
			  );
			}

			return new \Symfony\Component\HttpFoundation\Response($app['twig']->render('detail.htm',array("revisions"=>$revs,'id'=>$app['request']->get('id'),"tree"=>$tree)));
		})->assert('id','\d+');

		$collection->get("/{id}/{commit_id}",function(\Silex\Application $app){
			$repo = new \Git2\Repository(\REPOSITORY_DIRS . $app['request']->get('id').".git");
			$head = \Git2\Reference::lookup($repo,"refs/heads/master");
			$head = $head->resolve();
			$walker = new \Git2\Walker($repo);
			$walker->push($head->getTarget());
			$revs = array();
			$i = 0;
			foreach($walker as $entry){
			  if($i>20) break;
			  $revs[] = $entry;
			  $i++;
			}

			$commit = $repo->lookup($app['request']->get('commit_id'));
			$tmp_tree = $commit->getTree();
			$tree = array();
			foreach($tmp_tree as $t){
			  $tree[] = array(
				"entry" => $t,
				"object" => $repo->lookup($t->oid),
			  );
			}

			return new \Symfony\Component\HttpFoundation\Response($app['twig']->render('detail.htm',array("revisions"=>$revs,'id'=>$app['request']->get('id'),"tree"=>$tree)));
		})->assert('id','\d+');

		return $collection;
	}
}