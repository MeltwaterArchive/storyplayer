# add Webtatic repo for PHP 5.5 packages
rpm -Uvh https://mirror.webtatic.com/yum/el6/latest.rpm

# install Nginx
yum install -y nginx

# configure SSL support
mkdir /etc/nginx/ssl
chmod 700 /etc/nginx/ssl
cp /vagrant/src/tests/stories/ssl/nginx.crt /etc/nginx/ssl
cp /vagrant/src/tests/stories/ssl/nginx.key /etc/nginx/ssl
cp /vagrant/src/tests/stories/ssl/default.conf /etc/nginx/conf.d/
/etc/init.d/nginx restart

# install libzmq4 stable
git clone https://github.com/zeromq/zeromq4-x.git
( cd zeromq4-x && ./autogen.sh && ./configure && make install )

# install PHP 5.5 from Webtatic repo
yum install -y php55w php55w-devel php55w-pear
echo | pecl install zmq-1.1.2
cp /vagrant/src/tests/zmq/centos6-zmq.ini /etc/php.d/zmq.ini

# install Supervisor
yum install -y supervisor

# install our ZMQ echo server
cp /vagrant/src/tests/zmq/zmq-echo-server.php /usr/local/bin/zmq-echo-server.php
cp /vagrant/src/tests/zmq/zmq-echo-server.conf /etc/supervisor.d/zmq-echo-server.conf

# now we're ready to start supervisor
/etc/init.d/supervisord start