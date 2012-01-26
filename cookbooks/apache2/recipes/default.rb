#
# Cookbook Name:: apache2
# Recipe:: default
#
# Copyright 2012, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
package 'libapache2-mod-php5'

cookbook_file "/etc/apache2/sites-available/kokuban" do
  source "kokuban"
end

script "enable_modules" do
  interpreter "bash"
  user "root"
  cwd "/tmp"
  code <<-EOH
    a2dissite 000-default
    a2ensite kokuban
    a2enmod rewrite
  EOH
end
