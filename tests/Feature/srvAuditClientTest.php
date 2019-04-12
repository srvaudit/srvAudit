<?php
use Tests\TestCase;

class srvAuditClientTest extends TestCase
{
    /** @test */
    public function itCreatesAJiraIssue()
    {
        exec('/var/www/tests/Feature/client/new_jira_issue.exp 22 cotton@192.168.42.178',$out);
        foreach($out as $line) {
            if(strpos($line, 'SRVAUD') !== false ) {
                $ticket = substr($line, strpos($line, "SRVAUD"), 10);
            }
        }
        exec("jira view $ticket", $haystack);
        $this->assertContains('Unit test', $haystack[18]);
        $this->assertContains('free -m', $haystack[23]);
    }

    /** @test 
    public function itCreatesARedmineIssue()
    {
        exec('/var/www/tests/Feature/client/new_issue.exp 2224 srvaudit@dev.srvaudit.com',$out);
        foreach($out as $line) {
            if($url = strstr($line, "dev.srvaudit.com:8889/issues")) {
                exec("curl --silent $url", $haystack);
                $pieces = explode('/',$url);

                $handle = fopen('/tmp/test', "w");
                fwrite($handle, $pieces[2]);
                fclose($handle);
            }
        }
        $this->assertContains('Unit test ticket', $haystack[138]);
        $this->assertContains('Status</strong> changed from <i>New</i> to <i>In Progress', $haystack[169]);
        $this->assertContains('Unit test notes', $haystack[185]);
        $this->assertContains('free -m', $haystack[189]);
    }

    /** @test 
    public function itUpdatesARedmineIssue()
    {
        $handle = fopen('/tmp/test', "r");
        $id = fread($handle, 256);
        exec("/var/www/tests/Feature/client/update_issue.exp vagrant@192.168.42.210 $id",$out);
        $url = "192.168.42.210/issues/".$id;
        exec("curl --silent $url", $haystack);

        $this->assertContains('Status</strong> changed from <i>In Progress</i> to <i>Resolved', $haystack[214]);
        $this->assertContains('Unit test update notes', $haystack[219]);
        $this->assertContains('df -h', $haystack[223]);
    }*/
}
