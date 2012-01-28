<?php
namespace Kokuban;

class SmartprotocolProvider implements \Silex\ControllerProviderInterface
{
	public function initialize(\Silex\Application $app)
	{
		return $app;
	}

	public function connect(\Silex\Application $app)
	{
		$app = $this->initialize($app);
		$collection = new \Silex\ControllerCollection();

		$collection->match("/{repo}/{task}",function($repo,$task){

				$repository = new \Git2\Repository(REPOSITORY_DIRS . "{$repo}");

				switch ($task) {
					case "HEAD":
						if (is_file(REPOSITORY_DIRS . "{$repo}/HEAD")) {
							echo file_get_contents(REPOSITORY_DIRS . "{$repo}/HEAD");
						}
						break;

					case "git-receive-pack":
						$input = file_get_contents("php://input");
						header("Content-type: application/x-git-receive-pack-result");
						$input = gzBody($input);

						$descriptorspec = array(
						0 => array("pipe", "r"),
						1 => array("pipe", "w"),
						);

						$p = proc_open("git-receive-pack --stateless-rpc " . REPOSITORY_DIRS . "{$repo}",$descriptorspec,$pipes);
						if (is_resource($p)){
							fwrite($pipes[0],$input);
							fclose($pipes[0]);
							while (!feof($pipes[1])) {
								$data = fread($pipes[1],8192);
								echo $data;
							}
							fclose($pipes[1]);
							proc_close($p);
						}
						exit;
						break;

					case "git-upload-pack":
						$input = file_get_contents("php://input");
						header("Content-type: application/x-git-upload-pack-result");
						$input = gzBody($input);

						$descriptorspec = array(
							0 => array("pipe", "r"),
							1 => array("pipe", "w"),
						);

						$p = proc_open("git-upload-pack --stateless-rpc " . REPOSITORY_DIRS . "{$repo}",$descriptorspec,$pipes);
						if (is_resource($p)){
							fwrite($pipes[0],$input);
							fclose($pipes[0]);
							while (!feof($pipes[1])) {
								$data = fread($pipes[1],8192);
								echo $data;
							}
							fclose($pipes[1]);
							proc_close($p);
						}
						exit;
						break;

					case "info/refs":

						if ($_REQUEST["service"] == "git-upload-pack") {
							$input = file_get_contents("php://input");
							header("Content-type: application/x-git-upload-pack-advertisement");

							$descriptorspec = array(
								0 => array("pipe", "r"),
								1 => array("pipe", "w"),
							);

							$p = proc_open("git-upload-pack --stateless-rpc --advertise-refs " . REPOSITORY_DIRS . "{$repo}",$descriptorspec,$pipes);
							if (is_resource($p)){
								fwrite($pipes[0],$input);
								fclose($pipes[0]);
								$data = stream_get_contents($pipes[1]);
								fclose($pipes[1]);
								proc_close($p);

								$str = "# service=git-upload-pack\n";
								$data = str_pad(base_convert(strlen($str)+4, 10, 16),4,'0',STR_PAD_LEFT) . $str . '0000' . $data;
								header("Content-length: " . strlen($data));
								echo $data;
								exit;
							}
						} else if ($_REQUEST["service"] == "git-receive-pack") {
							$input = file_get_contents("php://input");
							header("Content-type: application/x-git-receive-pack-advertisement");

							$descriptorspec = array(
								0 => array("pipe", "r"),
								1 => array("pipe", "w"),
							);

							$p = proc_open("git-receive-pack --stateless-rpc --advertise-refs " . REPOSITORY_DIRS . "{$repo}",$descriptorspec,$pipes);
							if (is_resource($p)){
								fwrite($pipes[0],$input);
								fclose($pipes[0]);
								$data = stream_get_contents($pipes[1]);
								fclose($pipes[1]);
								proc_close($p);

								$str = "# service=git-receive-pack\n";
								$data = str_pad(base_convert(strlen($str)+4, 10, 16),4,'0',STR_PAD_LEFT) . $str . '0000' . $data;
								header("Content-length: " . strlen($data));
								echo $data;
								exit;
							}
						} else {
							foreach(\Git2\Reference::each($repository) as $ref) {
								printf("%s\t%s\n",$ref->oid,$ref->name);
							}
						}
					break;
				}
			})->assert('task','.+');

		return $collection;
	}
}

function gzBody($gzData){
	if(substr($gzData,0,3)=="\x1f\x8b\x08"){
		$i=10;
		$flg=ord(substr($gzData,3,1));
		if($flg>0){
			if($flg&4){
				list($xlen)=unpack('v',substr($gzData,$i,2));
				$i=$i+2+$xlen;
			}
			if($flg&8) $i=strpos($gzData,"\0",$i)+1;
			if($flg&16) $i=strpos($gzData,"\0",$i)+1;
			if($flg&2) $i=$i+2;
		}
		return gzinflate(substr($gzData,$i,-8));
	}
	else return $gzData;
}
