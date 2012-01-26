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
    git clone https://github.com/chobie/kokuban.git
  EOH
end
