#
# Cookbook Name:: kokuban
# Recipe:: default
#
# Copyright 2012, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

script "install_kokuban" do
  interpreter "bash"
  user "vagrant"
  cwd "/home/vagrant"
  code <<-EOH
    if [ -d "kokuban" ];
    then
      cd kokuban
      git pull
      git submodule init
      git submodule update
    else
      git clone https://github.com/chobie/kokuban.git
      cd kokuban
      git submodule init
      git submodule update
    fi
  EOH
end
