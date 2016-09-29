su root
dnf install php
dnf install mariadb-server
systemctl enable mariadb 
systemctl start mariadb
systemctl start httpd
journalctl -f
exit
javac
cd android-studio
cd bin
chmod u+x studio.sh
./studio.sh
cd /usr/lib/jvm
ls -al
set |more
JAVA_HOME=/usr/lib/java-1.8.0-openjdk-1.8.0.92-3.b14.fc24.x86_64
javac -version
cd /home/james
whereis flipper.jar
iptables -L
iptables -A INPUT -m state --state NEW -p tcp --dport 80 -j ACCEPT
iptables -A INPUT -m state --state NEW -p tcp --dport 3306 -j ACCEPT
exit


