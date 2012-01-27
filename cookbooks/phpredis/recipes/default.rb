#
# Cookbook Name:: phpredis
# Recipe:: default
#
# Copyright 2012, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
script "install_phpredis" do
  interpreter "bash"
  user "root"
  cwd "/tmp"
  code <<-EOH
    git clone https://github.com/nicolasff/phpredis.git 
    cd phpredis
    phpize --clean
    phpize
    ./configure
    make
    make test
    make install
  EOH
end

cookbook_file "/etc/php5/conf.d/redis.ini" do
  source "redis.ini"
  mode "0644"
end
