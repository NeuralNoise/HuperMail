un=$1
pass=$2
sudo useradd $un 
sudo /bin/echo "$un:$pass" | sudo /usr/sbin/chpasswd
sudo setquota -u $un 921600 1048576 0 0 -a /dev/loop0

