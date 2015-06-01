# update the definitions
apt-get update

# make sure we have compilers and such like
apt-get install -y build-essential pkg-config git autoconf automake libtool m4

# for some reason, Ubuntu 15.04 ships with 'libtool' renamed to be 'libtoolize'
# this change is something that isn't supported by the source code that we
# need to compile :(
if [[ -f /usr/bin/libtoolize ]] && ! -f [[ /usr/bin/libtool ]] ; then
	ln -s /usr/bin/libtoolize /usr/bin/libtool
fi

# install Nginx
apt-get install -y nginx

# configure SSL support
mkdir /etc/nginx/ssl
chmod 700 /etc/nginx/ssl
cp /vagrant/files/ssl/nginx.crt /etc/nginx/ssl
cp /vagrant/files/ssl/nginx.key /etc/nginx/ssl
cp /vagrant/files/ssl/default.conf /etc/nginx/conf.d/
/etc/init.d/nginx restart

# install libzmq4 stable
git clone https://github.com/zeromq/zeromq4-x.git
( cd zeromq4-x && ./autogen.sh && ./configure && make install )

# install PHP 5.5
apt-get install -y php5-cli php5-dev php-pear
echo | pecl install zmq-1.1.2
cp /vagrant/files/zmq/zmq.ini /etc/php5/mods-available/zmq.ini
php5enmod zmq

# install Supervisor
apt-get install -y supervisor

# install our ZMQ echo server
cp /vagrant/files/zmq/zmq-echo-server.php /usr/local/bin/zmq-echo-server.php
cp /vagrant/files/zmq/zmq-echo-server.conf /etc/supervisor/conf.d/zmq-echo-server.conf

# install other tools that we need
apt-get install -y screen

# now we're ready to start supervisor
/etc/init.d/supervisor restart