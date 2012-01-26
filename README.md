## Darling, i packed you a couple gist for lunch

php-git2 Example Application - Kokuban is yet another implementation of Gist -

# Install
 
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

will be add...

* solr integration
* store user data via Redis (as i love redis so mutch)

# License

MIT License.

