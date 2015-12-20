# start supervisor
supervisord -c /etc/supervisord.conf

# finally, we need to get rid of the default firewall
service iptables save
service iptables stop
chkconfig iptables off