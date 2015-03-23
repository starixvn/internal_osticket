<? if(!defined('OSTSCPINC') || !is_object($thisuser) || !$thisuser->isStaff() || !is_object($nav)) die('Access Denied'); ?>
<!DOCTYPE HTML PUBLIC>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<?php
if(defined('AUTO_REFRESH') && is_numeric(AUTO_REFRESH_RATE) && AUTO_REFRESH_RATE>0){ //Refresh rate
echo '<meta http-equiv="refresh" content="'.AUTO_REFRESH_RATE.'" />';
}
?>
<title>osTicket :: Staff Control Panel</title>
<link rel="stylesheet" href="css/main.css" media="screen">
<link href="css/jquery-ui-1.9.2.custom.css" rel="stylesheet">
<link href="css/select2.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css" media="screen">
<link rel="stylesheet" href="css/tabs.css" type="text/css">
<link rel="stylesheet" href="css/chosen.css" type="text/css">
<link rel="stylesheet" href="css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/scp.js"></script>
<script type="text/javascript" src="js/tabber.js"></script>
<script type="text/javascript" src="js/calendar.js"></script>
<script type="text/javascript" src="js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery-1.8.3.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js/chosen.jquery.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js/select2.js" charset="utf-8"></script>

<?php
if($cfg && $cfg->getLockTime()) { //autoLocking enabled.?>
<script type="text/javascript" src="js/autolock.js" charset="utf-8"></script>
<?}?>
</head>
<body>
<?php
if($sysnotice){?>
<div id="system_notice"><?php echo $sysnotice; ?></div>
<?php 
}?>
<div id="container">
    <div id="header">
        <a id="logo" href="index.php" title="osTicket"><img src="images/ostlogo.jpg" width="188" height="72" alt="osTicket"></a>
        <p id="info">Welcome back, <strong><?=$thisuser->getUsername()?></strong> 
           <?php
            if($thisuser->isAdmin() && !defined('ADMINPAGE')) { ?>
            | <a href="admin.php">Admin Panel</a> 
            <?}else{?>
            | <a href="index.php">Staff Panel</a>
            <?}?>
            | <a href="profile.php?t=pref">My Preference</a> | <a href="logout.php">Log Out</a></p>
    </div>   
    <div id="page-tabs">
        <ul id="main_nav_new">
            <?php
            if(($tabs=$nav->getTabs()) && is_array($tabs)){
                $total = count($tabs);
                $i = 0;
             foreach($tabs as $tab) { 
                  if($i < $total-1) {
            ?>           
                    <li <?=$tab['active']?'class="selected"':''?>>
                        <div></div>		
                        <a href="<?=$tab['href']?>" title="<?=$tab['title']?>"><?=$tab['desc']?></a></li>
          
             <?php
                  } else {
             ?>
                     <li <?=$tab['active']?'class="selected last"':'class="last"'?> >
                         <div></div>
                         <a href="<?=$tab['href']?>" title="<?=$tab['title']?>"><?=$tab['desc']?></a>
                         <div class="last"></div>
                     </li> 
             <?php
                   }
                 $i++;
              }
            }else{ //?? ?>
                <li><a href="profile.php" title="My Preference">My Account</a></li>
            <?}?>
        </ul>
        
        <ul id="sub_nav">
             <?php
             if(($subnav=$nav->getSubMenu()) && is_array($subnav)){
               foreach($subnav as $item) { ?>
                 <li><a class="<?=$item['iconclass']?>" href="<?=$item['href']?>" title="<?=$item['title']?>"><?=$item['desc']?></a></li>
               <?}
             }?>
         </ul>
        
    </div>
    <!--<div class="clear"></div>-->
    <div id="content" width="100%">
        <div id="mess-dialog"></div>
