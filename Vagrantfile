Vagrant::Config.run do |config|
  config.vm.box     = "ubuntu-1104-server-amd64"
  config.vm.box_url = "http://dl.dropbox.com/u/7490647/talifun-ubuntu-11.04-server-amd64.box"
  config.vm.forward_port 80, 8081

  config.vm.provision :chef_solo do |chef|
    chef.cookbooks_path = "cookbooks"
    chef.add_recipe "apt"
    chef.add_recipe "php5"
    chef.add_recipe "pcre-dev"
    chef.add_recipe "apache2"
    chef.add_recipe "cmake"
    chef.add_recipe "git"
    chef.add_recipe "libgit2"
    chef.add_recipe "php-git2"
    chef.add_recipe "kokuban"
    chef.add_recipe "finalize"
	 
    # You may also specify custom JSON attributes:
    # chef.json = { :mysql_password => "foo" }
  end
  
  config.vm.customize [
    "modifyvm", :id,
    "--memory","1024",
    "--name","kokuban"
  ]

end
