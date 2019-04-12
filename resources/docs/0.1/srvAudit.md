<img src="https://srvaudit.com/img/srvAudit_lgr.png" srcset="https://srvaudit.com/img/srvAudit_lgr.png 1x, https://srvaudit.com/img/srvAudit_white2x.png 2x, https://srvaudit.com/img/srvAudit_white2x.png 3x, https://srvaudit.com/img/srvAudit_white2x.png 4x" height="65" width="333" style="margin-left:-10px" alt="srvAudit"/>

# Why Audit?
--------

Session and command auditing is an integral part of change control required for many audit standards, as well as a crucial defense against unwanted intrusions.  With the ever increasing number of cyber attacks, it is increasingly important for all organizations to secure their data against economic sabotage.  srvAudit's host session logging can help quickly detect unwanted logins and transfer critical intrusion evidence off-host in real time.  srvAudit also will help your infrastructure meet change recording and other requirements for several auditing standards including SSAE-16 SOC1 / SOC2 Type II, SAS-70, HIPAA, and others.

# Features
--------

srvAudit integrates with popular project management, messaging, and monitoring services, giving your team up-to-the-second infrastructure activity as it happens.  srvAudit allows you to send session and command history from multiple hosts to our supported integrations, allowing you to log and monitor shell activity across your fleet of hosts in a centralized location.

srvAudit interrupts the normal shell login process, forcing the user to input a ticket number or opt to create new ticket upon login.  Users can update ticket status and add session notes upon shell logout.

We currently support sending session and command information to Slack, Jira, Redmine, Datadog, and New Relic services.

### Project Management

**Jira**

* Force issue-based session tracking
* Session host
* Session start time
* Session end time
* Session and command duration
* Command, plus cwd/time/exit code

**Redmine**

* Force issue-based session tracking
* Session host
* Session start time
* Session end time
* Session and command duration
* Command, plus cwd/time/exit code
* Issue status updates

### Messaging and Chat

**Slack**

* Session host
* Session start time
* Session end time
* Session notes
* Link to ticket system

### Systems metrics, monitoring, and alerting

**Datadog** (coming soon)

* Session host
* Session start time
* Session end time
* Commands with timestamp and duration

**New Relic** (coming soon)

* Session host
* Session start time
* Session end time

### CLI tools
* saticket command
    * update your ticket status
    * switch to another ticket
    * create a new ticket

### Advanced Shell History (ASH)

[ASH](https://github.com/barabo/advanced-shell-history) logs the following command history metadata to a local sqlite database per user, located in ~/.ash/history.db.  You can query this DB with ASH commands or sqlite directly.

* command current working directory.
* exit code
* pipe state
* command start/stop times with duration
* controlling tty
* ssh connection details
* [ASH details](https://github.com/barabo/advanced-shell-history#kicking-the-tires)

# srvAudit Tester
------------
We use Docker to create our test environment.  You will need to install [Docker](https://docs.docker.com/install) and [Docker Compose](https://docs.docker.com/compose/install).  Once Docker is installed, download our test repository, which includes Docker services for a srvAudit-enabled Ubuntu container, a Redmine container to test project management system integration, and a MySQL container for the Redmine database. 

`DOCKER_IP` is the host where you are running the service composure.  If you are running Docker locally for testing, simply replace `DOCKER_IP` with `localhost`.

Check out the srvAudit Tester repository, create /data and give appropriate permissions for Docker access. Then clone the srvAudit Tester repository, enter the checked out folder, and start the Docker composure.

```
git clone http://git.srvaudit.com/srvaudit/tester.git
cd tester
docker-compose up -d
```
Complete the following steps to set up your test environment.

* Visit <http://DOCKER_IP:8889/login>, log in with username: admin, password: admin, then update the admin password when prompted.
* Visit <http://DOCKER_IP:8889/projects/new> and create a new project without spaces in the name.
* Visit <http://DOCKER_IP:8889/admin> and click "Load the default configuration".
* Click Settings -> API, then check "Enable REST web service", then click Save.
* Visit <http://DOCKER_IP:8889/my/account>, in the right column under API access key, click Show.
* Copy API key for client for next command.
* Update API key in client container, replacing "API KEY HERE" with the key you copied in the previous step.

```
docker exec srvaudittester_client_1 sed -i 's/redmineKey=""/redmineKey="API KEY HERE"/g' /etc/srvaudit/config
```

* Test SSH login with these credentials.  srvAudit will prompt you for ticket information.  Follow instructions to create a new Redmine issue via the srvAudit login, or enter a previously created ticket number.

```
username: srvaudit
password: abc123
ssh -p2222 srvaudit@localhost
```


# Client Installation - Ubuntu 16.04+
------------

We are only supporting the latest Ubuntu operating systems currently. The supported OS list will grow once we have CI testing in place. We plan on supporting CentOS next.

The srvAudit installer will prompt you for the following pieces of information depending on the integrations you wish to use. Please have these ready before installing.

**Jira**

* Jira URL
* Jira project key

**Redmine**

* Redmine URL
* Redmine API key

**Slack**

* Slack webhook URL
* Slack channel

**New Relic**

* New Relic License Key

<span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span> &nbsp;Install on Ubuntu 14.04 or newer

    curl -sSL https://srvaudit.com/install | sudo sh

<span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span> &nbsp;Install on CentOS 7

    curl -sSL --ciphers ecdhe_ecdsa_aes_128_sha https://srvaudit.com/installcentos | sudo sh

srvAudit will prompt you to enter a ticket number or create a new ticket during the next login. 

The installer performs the following actions:

* Installs dependencies with OS package manager.
    * Ubuntu - unzip zip build-essential git flex wget sqlite3 subversion python2.7-dev jshon
    * CentOS - unzip zip git flex wget sqlite3 subversion python2.7-dev policycoreutils-python, and groupinstall "Development Tools"
* CentOS only:
    * Compiles and installs [Jshon](https://github.com/keenerd/jshon).
    * Compiles and installs [Jansson](http://www.digip.org/jansson).
    * Installs srvAudit SELinux profile.
* Checks out latest stable srvAudit release.
* Compiles and [installs](https://code.google.com/p/advanced-shell-history/source/browse/trunk/Makefile "ASH install script") Advanced Shell History.
* Adds the `/var/log/srvaudit.log` file.
* Adds the `/etc/logrotate.d/srvaudit` file.
* Adds `/srv/audit/hello` to `/etc/shells`.
* Replaces all instances of `/bin/bash` to `/srv/audit/hello` inside the `/etc/passwd` file.
* Adds logout script to `/etc/bash[rc|.bashrc]`.
* Remove MOTD from the `/etc/ash/ash.conf` file.
* Adds `/tmp/srvHistory` tmp file.
* Prompts for integrations authentication keys and URLs.
* Writes the `/etc/srvaudit/config` file.

# Usage
-----

Simply log into any tty device (usually via ssh, or the main console), and you will be prompted for a ticket number or to create a new ticket.  When logging out of the session, you will be prompted for session notes. Integration APIs will be pinged with supported information types.

See `/etc/srvaudit/config` for integration configurations post-install.

Use `ash_query -q ME` to see Advanced Session History.  See [ASH docs](https://code.google.com/p/advanced-shell-history/wiki "ASH Documentation") for more ASH-specific information and usage.

# Known Issues
------------

* No security review yet.  srvAudit is not to be considered a security tool at our current early stage of development.  Still, there can be many benefits from this tool.  We plan to undergo a independent 3rd party security review once we stabilize.
* Any interactive logins or automated SSH processes will need to send a ticket number after login, using the [expect](http://expect.sourceforge.net/) utility, for example.

# Troubleshooting
---------------

If you encounter issues, mount the root filesystem using a rescue disk and edit /etc/passwd, updating users to use /bin/bash instead of /srv/audit/hello. You can use this command to update `/etc/passwd` to the default shell of `/bin/bash`.

    perl -pi -e 's/\/srv\/audit\/hello/\/bin\/bash/' /etc/passwd

Remove srvAudit quickly and completely by running the following command:

    /srv/audit/uninstall[centos]

# Contribute
----------

* [Client issue tracker](https://github.com/srvAudit/srvAudit/issues)
* [Client source code repo](https://github.com/srvAudit/srvAudit)
* [Documentation repo](https://github.com/srvAudit/srvAuditDocs)

# Support
-------

If you are having issues, please let us know by emailing support@srvaudit.com

# Credit
------

     0/ A srvAudit core component is Advanced Shell History.
    <Y  https://code.google.com/p/advanced-shell-history
    / \

# License
-------

The project is licensed under the MIT license.
