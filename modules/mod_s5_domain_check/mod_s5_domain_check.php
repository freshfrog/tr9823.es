<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

$label		= $params->get( 'label', '' );
$buttontext		= $params->get( 'buttontext', '' );
$resulttext		= $params->get( 'resulttext', '' );
$checkall		= $params->get( 'checkall', '' );
$pretext		= $params->get( 'pretext', '' );
$forwardurl		= $params->get( 'forwardurl', '' );
$s5_domain1		= $params->get( 's5_domain1', '' );
$s5_break1		= $params->get( 's5_break1', '' );
$s5_domain2		= $params->get( 's5_domain2', '' );
$s5_break2		= $params->get( 's5_break2', '' );
$s5_domain3		= $params->get( 's5_domain3', '' );
$s5_break3		= $params->get( 's5_break3', '' );
$s5_domain4		= $params->get( 's5_domain4', '' );
$s5_break4		= $params->get( 's5_break4', '' );
$s5_domain5		= $params->get( 's5_domain5', '' );
$s5_break5		= $params->get( 's5_break5', '' );
$s5_domain6		= $params->get( 's5_domain6', '' );
$s5_break6		= $params->get( 's5_break6', '' );
$s5_domain7		= $params->get( 's5_domain7', '' );
$s5_break7		= $params->get( 's5_break7', '' );
$s5_domain8		= $params->get( 's5_domain8', '' );
$s5_break8		= $params->get( 's5_break8', '' );
$s5_domain9		= $params->get( 's5_domain9', '' );
$s5_break9		= $params->get( 's5_break9', '' );
$s5_domain10	= $params->get( 's5_domain10', '' );
$s5_break10		= $params->get( 's5_break10', '' );
$s5_domain11	= $params->get( 's5_domain11', '' );
$s5_break11		= $params->get( 's5_break11', '' );
$s5_domain12	= $params->get( 's5_domain12', '' );
$s5_break12		= $params->get( 's5_break12', '' );
$s5_domain13	= $params->get( 's5_domain13', '' );
$s5_break13		= $params->get( 's5_break13', '' );
$s5_domain14	= $params->get( 's5_domain14', '' );
$s5_break14		= $params->get( 's5_break14', '' );
$s5_domain15	= $params->get( 's5_domain15', '' );
$s5_break15		= $params->get( 's5_break15', '' );

?>

<?php if ($pretext != "") { ?>
<div style="margin-bottom:8px">
<?php echo $pretext ?>
</div>
<?php } ?>

<?php

    function checkDomain($domain,$server,$findText){
        // Open a socket connection to the whois server
        $con = fsockopen($server, 43);
        if (!$con) return false;
        
        // Send the requested doman name
        fputs($con, $domain."\r\n");
        
        // Read and store the server response
        $response = ' :';
        while(!feof($con)) {
            $response .= fgets($con,128); 
        }
        
        // Close the connection
        fclose($con);
        
        // Check the response stream whether the domain is available
		// echo $response;
        if (strpos($response, $findText)){
		
            return true;
        }
        else {
            return false;   
        }
    }
    
    function showDomainResult($domain,$server,$findText){
	
       if (checkDomain($domain,$server,$findText)){
          echo "<div style='margin-bottom:4px'>$domain Available</div>";
       }
       else echo "<div style='margin-bottom:4px'>$domain Taken</div>";
    }
	
?>

    <div id="main">
      <form action="<?php echo $forwardurl ?>" method="post" name="domain" id="domain">
	  <span style="font-weight:bold">
        <?php echo $label ?> :
	  </span>
		<div style="margin-top:8px; margin-bottom:8px">
				<input class="text" name="domainname" type="text" />
		</div>
		<div style="margin-top:8px; margin-bottom:8px">
		<div style="margin-bottom:8px">
                <input type="checkbox" name="all" checked="checked"/> <?php echo $checkall ?>
		</div>
		<?php if ($s5_domain1 == "yes") { ?>	
                <input type="checkbox" name="com"/> .com
				<?php if ($s5_break1 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain2 == "yes") { ?>	
                <input type="checkbox" name="net"/> .net
				<?php if ($s5_break2 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain3 == "yes") { ?>	
                <input type="checkbox" name="info"/> .info
				<?php if ($s5_break3 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain4 == "yes") { ?>	
                <input type="checkbox" name="org"/> .org
				<?php if ($s5_break4 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain5 == "yes") { ?>	
                <input type="checkbox" name="biz"/> .biz
				<?php if ($s5_break5 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain6 == "yes") { ?>	
                <input type="checkbox" name="couk"/> .co.uk
				<?php if ($s5_break6 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain7 == "yes") { ?>	
                <input type="checkbox" name="name"/> .name
				<?php if ($s5_break7 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain8 == "yes") { ?>	
                <input type="checkbox" name="cc"/> .cc
				<?php if ($s5_break8 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain9 == "yes") { ?>	
                <input type="checkbox" name="us"/> .us
				<?php if ($s5_break9 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain10 == "yes") { ?>	
                <input type="checkbox" name="tv"/> .tv
				<?php if ($s5_break10 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain11 == "yes") { ?>	
                <input type="checkbox" name="eu"/> .eu
				<?php if ($s5_break11 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain12 == "yes") { ?>	
                <input type="checkbox" name="edu"/> .edu
				<?php if ($s5_break12 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain13 == "yes") { ?>	
                <input type="checkbox" name="mobi"/> .mobi
				<?php if ($s5_break13 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain14 == "yes") { ?>	
                <input type="checkbox" name="nl"/> .nl
				<?php if ($s5_break14 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		
		<?php if ($s5_domain15 == "yes") { ?>	
                <input type="checkbox" name="ca"/> .ca
				<?php if ($s5_break15 == "yes") { ?>
				<div style="height:8px"></div>
				<?php } ?>
		<?php } ?>
		</div>
				<input class="button" type="submit" name="submitBtn" value="<?php echo $buttontext ?>"/>
      </form>

	  
<?php    
    if (isset($_POST['submitBtn'])){
        $domainbase = (isset($_POST['domainname'])) ? $_POST['domainname'] : '';
        $d_all      = (isset($_POST['all'])) ? 'all' : '';   
		
		if ($s5_domain1 == "yes") {
        $d_com      = (isset($_POST['com'])) ? 'com' : ''; 
		}	    
		
		if ($s5_domain2 == "yes") {
        $d_net      = (isset($_POST['net'])) ? 'net' : ''; 
		}	
		
		if ($s5_domain3 == "yes") {
        $d_info      = (isset($_POST['info'])) ? 'info' : ''; 
		}	   
		
		if ($s5_domain4 == "yes") {
        $d_org      = (isset($_POST['org'])) ? 'org' : ''; 
		}	
		
		if ($s5_domain5 == "yes") {
        $d_biz      = (isset($_POST['biz'])) ? 'biz' : ''; 
		}
		
		if ($s5_domain6 == "yes") {
        $d_couk      = (isset($_POST['couk'])) ? 'couk' : ''; 
		}
		
		if ($s5_domain7 == "yes") {
        $d_name      = (isset($_POST['name'])) ? 'name' : ''; 
		}
		
		if ($s5_domain8 == "yes") {
        $d_cc      = (isset($_POST['cc'])) ? 'cc' : ''; 
		}
		
		if ($s5_domain9 == "yes") {
        $d_us      = (isset($_POST['us'])) ? 'us' : ''; 
		}
		
		if ($s5_domain10 == "yes") {
        $d_tv      = (isset($_POST['tv'])) ? 'tv' : ''; 
		}
		
		if ($s5_domain11 == "yes") {
        $d_eu      = (isset($_POST['eu'])) ? 'eu' : ''; 
		}
		
		if ($s5_domain12 == "yes") {
        $d_edu      = (isset($_POST['edu'])) ? 'edu' : ''; 
		}
		
		if ($s5_domain13 == "yes") {
        $d_mobi      = (isset($_POST['mobi'])) ? 'mobi' : ''; 
		}
		
		if ($s5_domain14 == "yes") {
        $d_nl      = (isset($_POST['nl'])) ? 'nl' : ''; 
		}
		
		if ($s5_domain15 == "yes") {
        $d_ca      = (isset($_POST['ca'])) ? 'ca' : ''; 
		}
        
        // Check domains only if the base name is big enough
        if (strlen($domainbase)>0){
?>
      <div id="caption" style="margin-top:8px; margin-bottom:8px; font-weight:bold"><?php echo $resulttext ?> :</div>
      <div id="result">

<?php        

			if ($s5_domain1 == "yes") {
            if (($d_com != '') || ($d_all != '') ) showDomainResult($domainbase.".com",'whois.crsnic.net','No match for');
			}	
			
			if ($s5_domain2 == "yes") {
            if (($d_net != '') || ($d_all != '') ) showDomainResult($domainbase.".net",'whois.crsnic.net','No match for');
			}	
			
			if ($s5_domain3 == "yes") {
            if (($d_info != '') || ($d_all != '') ) showDomainResult($domainbase.".info",'whois.afilias.net','NOT FOUND');
			}	
			
			if ($s5_domain4 == "yes") {
            if (($d_org != '') || ($d_all != '') ) showDomainResult($domainbase.".org",'whois.publicinterestregistry.net','NOT FOUND');
			}	
			
			if ($s5_domain5 == "yes") {
            if (($d_biz != '') || ($d_all != '') ) showDomainResult($domainbase.".biz",'whois.neulevel.biz','Not found:');
			}	
			
			if ($s5_domain6 == "yes") {
            if (($d_couk != '') || ($d_all != '') ) showDomainResult($domainbase.".co.uk",'whois.nic.uk','No match for');
			}	
			
			if ($s5_domain7 == "yes") {
            if (($d_name != '') || ($d_all != '') ) showDomainResult($domainbase.".name",'whois.nic.name','No match');
			}	
			
			if ($s5_domain8 == "yes") {
            if (($d_cc != '') || ($d_all != '') ) showDomainResult($domainbase.".cc",'whois.nic.cc','No match');
			}	
			
			if ($s5_domain9 == "yes") {
            if (($d_us != '') || ($d_all != '') ) showDomainResult($domainbase.".us",'whois.nic.us','Not found:');
			}
			
			if ($s5_domain10 == "yes") {
            if (($d_tv != '') || ($d_all != '') ) showDomainResult($domainbase.".tv",'whois.nic.tv','No match for');
			}
			
			if ($s5_domain11 == "yes") {
            if (($d_eu != '') || ($d_all != '') ) showDomainResult($domainbase.".eu",'whois.eu','FREE');
			}
			
			if ($s5_domain12 == "yes") {
            if (($d_edu != '') || ($d_all != '') ) showDomainResult($domainbase.".edu",'whois.crsnic.net','No match for');
			}
			
			if ($s5_domain13 == "yes") {
            if (($d_mobi != '') || ($d_all != '') ) showDomainResult($domainbase.".mobi",'whois.dotmobiregistry.net','NOT FOUND');
			}
			
			if ($s5_domain14 == "yes") {
            if (($d_nl != '') || ($d_all != '') ) showDomainResult($domainbase.".nl",'whois.domain-registry.nl','free');
			}
			
			if ($s5_domain15 == "yes") {
            if (($d_ca != '') || ($d_all != '') ) showDomainResult($domainbase.".ca",'whois.cira.ca','AVAIL');
			}

?>

     </div>
<?php            
        }
    }
?>    

    </div>
	