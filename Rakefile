task :sync do
  `vagrant ssh-config | sed 's/default/kokuban/'> .ssh_config`
  `rsync --delete --exclude=repos -e 'ssh -F .ssh_config' -avz . kokuban:/home/vagrant/kokuban/`
end
