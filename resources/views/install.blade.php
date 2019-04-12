#!/bin/bash

## Method to deal with user prompts
promptyn () {
  while true; do
    read -p "$1 " yn < /dev/tty
    case $yn in
      "" ) return 0;;
      [Yy]* ) return 0;;
      [Nn]* ) return 1;;
      * ) echo "Please answer yes or no.";;
    esac
  done
}

if [ $# -eq 0 ]; then
    if promptyn "Do you wish to install srvAudit? WARNING - THIS IS CURRENTLY BETA SOFTWARE. Though we strive to make srvAudit as stable as possible, there may be critical bugs. Install? (Y/n)"; then
        break
    else
        echo "Exiting, maybe next time..."
        exit
    fi
elif [ $1 = "-y" ]; then
    break
else
    echo "Script accepts '-y' argument for automated uninstall, or no arguments."
    exit
fi

if [ -f /usr/local/bin/srvaudit/uninstall ]; then
    /usr/local/bin/srvaudit/uninstall -y
fi

# deps deps deps
apt-get update && apt-get install software-properties-common --no-install-recommends -y
add-apt-repository ppa:adiscon/v8-stable -y
add-apt-repository universe
apt update && apt install -y --no-install-recommends \
        build-essential \
        ca-certificates \
        flex \
        git \
        jshon \
        libcurl4-gnutls-dev \
        locales \
        python2.7-dev \
        redis-server \
        sqlite3 \
        subversion \
        unzip \
        wget \
        zip \
    && locale-gen en_US en_US.UTF-8

## add ca-certificate for letsencrypt
hostname=srvaudit.com && port=443 && trust_cert_file_location=`curl-config --ca` && sudo bash -c "echo -n | openssl s_client -showcerts -connect $hostname:$port 2>/dev/null  | sed -ne '/-BEGIN CERTIFICATE-/,/-END CERTIFICATE-/p' >> $trust_cert_file_location"

## install srvAudit and ASH
# back up old install
mv /usr/local/bin/srvaudit /usr/local/bin/srvaudit.backup
cd /usr/local/bin && git clone https://git.srvaudit.com/srvaudit/srvaudit-client.git && rm -rf srvaudit && mv srvaudit-client srvaudit
mkdir -p '/usr/local/bin/srvaudit/ash/files/usr/local/bin'
cd - > /dev/null
cd /usr/local/bin/srvaudit/ash
sudo tar -xpv --no-same-owner -C / -f overlay.tar.gz
cd - > /dev/null
grep -v advanced_shell_history /etc/bash.bashrc > bashrc && chmod 0644 bashrc && mv bashrc /etc/bash.bashrc
cat >> /etc/bash.bashrc <<DELIM
source /usr/lib/advanced_shell_history/bash
DELIM

## Add config and log
touch /var/log/srvaudit.log
chmod 666 /var/log/srvaudit.log
mkdir -p /etc/srvaudit
cp /usr/local/bin/srvaudit/config_example /etc/srvaudit/config

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

## run srvaudit/hello upon login (server-wide)
grep -v audit /etc/shells > /tmp/shells && mv /tmp/shells /etc/shells
echo "/usr/local/bin/srvaudit/hello" >> /etc/shells
perl -pi -e 's/\/bin\/bash/\/usr\/local\/bin\/srvaudit\/hello/' /etc/passwd

## update sessionID on bash logout
cat > /etc/bash.bash_logout <<DELIM
echo \$ASH_SESSION_ID > /tmp/\$(whoami)SrvAuditSessionID
DELIM

## remove HISTCONTROL settings
perl -pi -e 's/HISTCONTROL/#HISTCONTROL/' /home/$(whoami)/.bashrc > /dev/null 2>&1 
perl -pi -e 's/HISTCONTROL/#HISTCONTROL/' /home/$(who am i|awk '{print $1}')/.bashrc > /dev/null  2>&1
perl -pi -e 's/HISTCONTROL/#HISTCONTROL/' /root/.bashrc > /dev/null 2>&1

## add bash timeout
echo "TMOUT=600" > /etc/profile.d/bashlogout.sh

## remove ASH welcome message
perl -pi -e 's/ASH_CFG_MOTD/#ASH_CFG_MOTD/' /etc/ash/ash.conf > /dev/null 2>&1

## set tmp history perms
touch /tmp/srvHistory
chmod 666 /tmp/srvHistory

## add /srv/audit to $PATH
perl -pi -e 's/="/="\/usr\/local\/bin\/srvaudit\/bin:/' /etc/environment > /dev/null 2>&1

## jira
wget https://github.com/Netflix-Skunkworks/go-jira/releases/download/v1.0.15/jira-linux-amd64
mv jira-linux-amd64 /usr/sbin/jira && chmod +x /usr/sbin/jira

## add systemd service
mv /usr/local/bin/srvaudit/srvaudit.service /etc/systemd/system/

## install SCP only
git clone https://github.com/scponly/scponly.git && cd scponly && ./configure --enable-scp-compat --enable-rsync-compat && make && make install && cd .. && rm -rf scponly
echo "/usr/local/bin/scponly" >> /etc/shells
useradd scp 
mkdir /home/scp
chown scp:scp /home/scp
perl -pi -e's/home\/scp:/home\/scp:\/usr\/local\/bin\/scponly/g' /etc/passwd

## check configuration
if grep -qx "initialConfig=1" /etc/srvaudit/config; then
    :
else
    ## srvAudit Banner
    if grep -qx "showBanner=" /etc/srvaudit/config; then
        if promptyn "Show srvAudit banner on login? (Y/n)"; then
            sed -i "s@showBanner=@showBanner=1@g" /etc/srvaudit/config
            echo ""
        fi
    fi

    ## srvAudit
    if grep -qx "srvAuditEnabled=" /etc/srvaudit/config; then
        if promptyn "Configure srvAudit? (Y/n)"; then
            read -r -p "srvAudit URL, including protocol (include https://): " srvAuditURL < /dev/tty
            read -r -p "srvAudit API key: " srvAuditKey < /dev/tty
            sed -i "s@srvAuditEnabled=@srvAuditEnabled=1@g" /etc/srvaudit/config
            sed -i "s@srvAuditURL=\"\"@srvAuditURL=\""$srvAuditURL"\"@g" /etc/srvaudit/config
            sed -i "s@srvAuditKey=\"\"@srvAuditKey=\""$srvAuditKey"\"@g" /etc/srvaudit/config
            echo ""
        else
            sed -i "s@srvAuditEnabled=@srvAuditEnabled=0@g" /etc/srvaudit/config
            echo ""
        fi
    fi

    ## Jira
    if grep -qx "pms=\"\"" /etc/srvaudit/config; then
        if promptyn "Configure Jira? (Y/n)"; then
            read -r -p "Jira URL, including protocol (include https://): " jiraURL < /dev/tty
            read -r -p "Jira project key: " jiraKey < /dev/tty
            sed -i "s@pms=\"\"@pms=\"jira\"@g" /etc/srvaudit/config
            sed -i "s@jiraURL=\"\"@jiraURL=\""$jiraURL"\"@g" /etc/srvaudit/config
            sed -i "s@jiraProjectKey=\"\"@jiraProjectKey=\""$jiraKey"\"@g" /etc/srvaudit/config
            echo ""
        else
            echo ""
        fi
    fi

    ## Redmine
    if grep -qx "pms=\"\"" /etc/srvaudit/config; then
        if promptyn "Configure Redmine? (Y/n)"; then
            read -r -p "Redmine URL, including protocol (http(s)://): " redmineURL < /dev/tty
            read -r -p "Redmine API key: " redmineKey < /dev/tty
            sed -i "s@pms=\"\"@pms=\"Redmine\"@g" /etc/srvaudit/config
            sed -i "s@redmineURL=\"\"@redmineURL=\""$redmineURL"\"@g" /etc/srvaudit/config
            sed -i "s@redmineKey=\"\"@redmineKey=\""$redmineKey"\"@g" /etc/srvaudit/config
            echo ""
        else
            echo ""
        fi
    elif grep -xq "pms=\"jira\"" /etc/srvaudit/config; then
        echo "Jira is configured, skipping Redmine configuration."
        echo ""
    fi

    ## No PMS
    if grep -qx "pms=\"\"" /etc/srvaudit/config; then
            sed -i "s@pms=\"\"@pms=\"0\"@g" /etc/srvaudit/config
    fi

    ### Slack
    if grep -qx "slackEnabled=" /etc/srvaudit/config; then
        if promptyn "Configure Slack? (Y/n)"; then
            read -r -p "Slack API URL, including protocol (https://): " slackURL < /dev/tty
            read -r -p "Slack chat channel (#channelname): " slackChannel < /dev/tty
            sed -i "s@slackEnabled=@slackEnabled=1@g" /etc/srvaudit/config
            sed -i "s@slackURL=\"\"@slackURL=\""$slackURL"\"@g" /etc/srvaudit/config
            sed -i "s@slackChannel=\"\"@slackChannel=\""$slackChannel"\"@g" /etc/srvaudit/config
            echo ""
        else
            sed -i "s@slackEnabled=@slackEnabled=0@g" /etc/srvaudit/config
            echo ""
        fi
    fi

    ## New Relic
    if grep -qx "newRelicEnabled=" /etc/srvaudit/config; then
        if promptyn "Configure New Relic? (Y/n)"; then
            read -r -p "New Relic License key: " newRelicLicenseKey < /dev/tty
            sed -i "s@newRelicEnabled=@newRelicEnabled=1@g" /etc/srvaudit/config
            sed -i "s@newRelicLicenseKey=\"\"@newRelicLicenseKey=\""$newRelicLicenseKey"\"@g" /etc/srvaudit/config
            echo ""
        else 
            sed -i "s@newRelicEnabled=@newRelicEnabled=0@g" /etc/srvaudit/config
            echo ""
        fi
    fi

    ## Start srvaudit service
    systemctl start srvaudit
    systemctl enable srvaudit

    ## set initialConfig flag so these prompts don't show on every login
    sed -i "s@initialConfig=@initialConfig=1@g" /etc/srvaudit/config
    echo "Configuration complete.  See /etc/srvaudit/config to update any configuration variables."
    echo ""
fi
