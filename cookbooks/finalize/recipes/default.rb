#
# Cookbook Name:: finalize
# Recipe:: default
#
# Copyright 2012, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
script "finalize" do
  interpreter "bash"
  user "root"
  cwd "/tmp"
  code <<-EOH
    /etc/init.d/apache2 restart
  EOH
end
