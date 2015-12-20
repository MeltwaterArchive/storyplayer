# start nginx
systemctl start nginx

# start supervisor
supervisord -c /etc/supervisord.conf

# get rid of the default firewall
systemctl stop firewalld