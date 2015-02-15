# install Nginx
yum install -y nginx

# configure SSL support
mkdir /etc/nginx/ssl
chmod 700 /etc/nginx/ssl
cp /vagrant/src/tests/stories/ssl/nginx.crt /etc/nginx/ssl
cp /vagrant/src/tests/stories/ssl/nginx.key /etc/nginx/ssl
cp /vagrant/src/tests/stories/ssl/default.conf /etc/nginx/conf.d/
/etc/init.d/nginx restart
