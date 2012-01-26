## Darling, i packed you a couple gist for lunch

php-git2 Example Application - Kokuban is yet another implementation of Gist -

# Auto Install

````
gem install vagrant --no-ri --no-rdoc
gem install chef --no-ri --no-rdoc

git clone https://github.com/chobie/kokuban.git
cd kokuban
vagrant up
# this may take 30 minutes over if you don't have the box.
# go to http://localhost:8081/ when install finished.
````

# Manual Install
 
````
git clone https://github.com/libgit2/libgit2.git
cd libgit2
mkdir build && cd libgit2
cmake ..
cmake --build .
sudo cmake --build . --target install
````

````
git clone https://github.com/libgit2/php-git.git
cd php-git
phpize
./configure
make
sudo make install
# put 'extension=git2.so' to your php config
````

````
cd /path/to/your/public/dir
git clone https://github.com/chobie/kokuban.git
#setup apache2
````

# Features

* create git
* http smart protocal
(git clone http://hostname/<id>.git)

will be add...

* solr integration
* store user data via Redis (as i love redis so mutch)

# License

MIT License.

