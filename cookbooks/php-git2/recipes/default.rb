#
# Cookbook Name:: php-git2
# Recipe:: default
#
# Copyright 2012, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
script "install_php-git2" do
  interpreter "bash"
  user "root"
  cwd "/tmp"
  code <<-EOH
    git clone https://github.com/libgit2/php-git.git
    cd php-git
    phpize --clean
    phpize
    ./configure
    make
    make test
    make install
  EOH
end

cookbook_file "/etc/php5/conf.d/git2.ini" do
  source "git2.ini"
  mode "0644"
end
