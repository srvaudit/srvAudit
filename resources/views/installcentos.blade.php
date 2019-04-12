#!/bin/bash

## Method to deal with user prompts
promptyn () {
  while true; do
    read -p "$1 " yn < /dev/tty
    case $yn in
      [Yy]* ) return 0;;
      [Nn]* ) return 1;;
      * ) echo "Please answer yes or no.";;
    esac
  done
}

if promptyn "Install? WARNING - MAY BREAK PRODUCTION SYSTEMS - REALLY. (Y/n)"; then
	break
else
	echo "Exiting, maybe next time..."
	exit
fi

# deps deps deps
yum install unzip zip git flex wget sqlite3 subversion python2.7-dev policycoreutils-python -y
yum groupinstall "Development Tools" -y

## add /srv/audit dir
rm -rf /srv/oldaudit
rm -rf /srv/srvaudit
mv /srv/audit /srv/oldaudit
cd /srv
git clone http://git.srvaudit.com/srvaudit/srvaudit-client.git
mv srvaudit audit
#cd /srv/audit
#git checkout v0.1
# jshon from source
cd /srv/audit && wget http://www.digip.org/jansson/releases/jansson-2.7.tar.gz && tar zxvf jansson-2.7.tar.gz && cd jansson-2.7 && ./configure && make && make install
cd /srv/audit && git clone https://github.com/keenerd/jshon.git && cd jshon && make && make install && cd -
cd /srv/audit/ash
mkdir -p '/srv/audit/ash/files/usr/local/bin'
make build_c && make install_c
cd - > /dev/null
grep -v advanced_shell_history /etc/bashrc > /tmp/bashrc && chmod 0644 /tmp/bashrc && mv /tmp/bashrc /etc/bashrc
cat >> /etc/bashrc <<DELIM
source /usr/lib/advanced_shell_history/bash
DELIM

## Add config and log
touch /var/log/srvaudit.log
chmod 666 /var/log/srvaudit.log
mkdir -p /etc/srvaudit
cp /srv/audit/config_example /etc/srvaudit/config

## add logrotate script
cat > /etc/logrotate.d/srvaudit <<DELIM
/var/log/srvaudit.log
{
        rotate 7
        daily
        missingok
        notifempty
        compress
        postrotate
                invoke-rc.d rsyslog reload > /dev/null
        endscript
}
DELIM

## run /srv/audit/hello upon login (server-wide)
grep -v audit /etc/shells > /tmp/shells && mv /tmp/shells /etc/shells
echo "/srv/audit/hello" >> /etc/shells
perl -pi -e 's/\/bin\/bash/\/srv\/audit\/hello/' /etc/passwd

## remove HISTCONTROL settings
perl -pi -e 's/HISTCONTROL/#HISTCONTROL/' /home/$(whoami)/.bashrc > /dev/null 2>&1 
perl -pi -e 's/HISTCONTROL/#HISTCONTROL/' /home/$(who am i|awk '{print $1}')/.bashrc > /dev/null  2>&1
perl -pi -e 's/HISTCONTROL/#HISTCONTROL/' /root/.bashrc > /dev/null 2>&1

## remove ASH welcome message
perl -pi -e 's/ASH_CFG_MOTD/#ASH_CFG_MOTD/' /etc/ash/ash.conf > /dev/null 2>&1

## set tmp history perms
touch /tmp/srvHistory
chmod 666 /tmp/srvHistory

## Add SELinux profile
#cat | audit2allow -M srvaudit <<DELIM
#module srvaudit 1.0;
#
#require {
#	type var_t;
#	type sshd_t;
#	class file getattr;
#}
#
##============= sshd_t ==============
#allow sshd_t var_t:file getattr;
#DELIM

## Redmine
if promptyn "Configure Redmine? (Y/n)"; then
	read -r -p "Redmine URL, including protocol (http(s)://): " redmineURL < /dev/tty
	read -r -p "Redmine API key: " redmineKey < /dev/tty
	sed -i "s@pms=\"\"@pms=\"Redmine\"@g" /etc/srvaudit/config
	sed -i "s@redmineURL=\"\"@redmineURL=\""$redmineURL"\"@g" /etc/srvaudit/config
	sed -i "s@redmineKey=\"\"@redmineKey=\""$redmineKey"\"@g" /etc/srvaudit/config
	echo "Redmine configuration written"
fi

### Slack
if promptyn "Configure Slack? (Y/n)"; then
	read -r -p "Slack API URL, including protocol (https://): " slackURL < /dev/tty
	read -r -p "Slack chat channel (#channelname): " slackChannel < /dev/tty
	sed -i "s@slackEnabled=0@slackEnabled=1@g" /etc/srvaudit/config
	sed -i "s@slackURL=\"\"@slackURL=\""$slackURL"\"@g" /etc/srvaudit/config
	sed -i "s@slackChannel=\"\"@slackChannel=\""$slackChannel"\"@g" /etc/srvaudit/config
	echo "Slack configuration written"
fi

## New Relic
if promptyn "Configure New Relic? (Y/n)"; then
	read -r -p "New Relic License key: " newRelicLicenseKey < /dev/tty
	sed -i "s@newRelicEnabled=0@newRelicEnabled=1@g" /etc/srvaudit/config
	sed -i "s@newRelicLicenseKey=\"\"@newRelicLicenseKey=\""$newRelicLicenseKey"\"@g" /etc/srvaudit/config
	echo "New Relic configuration written"
	continue
fi
