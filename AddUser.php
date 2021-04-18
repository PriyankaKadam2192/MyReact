<?php
echo "HI Priya";
include_once("class/CMaster.php");
$lCMaster = new CMaster();
$Date = date('Y-m-d H:i:s ', time());
$ipaddress = $_SERVER['REMOTE_ADDR'];
session_start();
$UserId = $_SESSION['USERID'];
$Name = $_SESSION['USERNAME'];	
if($_SESSION['USERID'] == "" && $_SESSION['USERNAME'] == "")
{
	header("location:login.php");
}
require_once("class/CLog.php");
$llog = new CLog();
$llog->AdminLog($UserId,$Name,"User List",$Date,$ipaddress);
if(isset($_GET['UID']))
{
	$UID = $_GET['UID'];
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" href="img/CTLLogo.png" type="image/png">
	<title>Users List</title>
	<link href="css/login.css?v=1.1" rel="stylesheet"/>
	<link href="css/Dashboard.css?v=1.1" rel="stylesheet"/>	
	<script src="js/ValMaster.js"></script>
	<script src="js/jQuery1.8.3.js"></script>
</head>
<body>
<form method="post">  
	<div class="divmain">
	   <?php 
	   
	   	if(isset($_GET['UID']))
		{
			$bclbl = "Update User Details";
		}
		else
		{
			$bclbl = "Add User";
		}
	   
		$bctxt = "Users List";
		$bctxt2 = "";
		$bclink = "UsersList.php";
		$bcmyname= $bclbl;
		require_once("header.php");
		
		if(isset($_GET['UID']))
		{
			$Userdetails = $lCMaster->GetUserDetails($UID);
			
			$larr = explode("*#",$Userdetails);
			
			$txtEmail = trim($larr[0]);
			$txtMobile = trim($larr[1]);
			$txtUserName = trim($larr[2]);
			$lhfRoleId = trim($larr[3]);
		}
		
		if(isset($_POST['btnSave']))
		{
			$txtUserName = trim($_POST['txtUserName']);
			$txtEmail = trim($_POST['txtEmail']);
			$txtMobile = trim($_POST['txtMobile']);
			$ddlStatus = trim($_POST['ddlStatus']);
			$lhfRoleId = $_POST['hfRoleId'];
			if($lhfRoleId == "")
			{
				$lhfRoleId = 1;
			}
			else
			{
				$lhfRoleId = $_POST['hfRoleId'];
			}
			
			$dupcnt = $lCMaster->chkdupuser($txtEmail,$txtMobile,$txtUserName);
			
			if($dupcnt > 0)
			{
				header("Location: AddUser.php?lerr=1");
			}
			else
			{
				$Password = $lCMaster->GenrateUserPass();
			
				$lCMaster->addUser($txtEmail,$txtMobile,$Password,$txtUserName,$Date,$ipaddress,$ddlStatus,$lhfRoleId);
			
			  	$lSubject = "Reconciliation Web Application Login Credentials";
						$lbody = "Dear $txtUserName,<br/><br/>Please Visit Link  http://103.227.62.218/Recon/  for Reconciliation Web Application.<br/><br/>Credentials for Login <br/><br/> User Name or Mobile No: $txtEmail / $txtMobile <br/><br/> Password :  $Password <br/><br/>Thanks & Regards,<br/>CTL Team";
						$lcc = "";
						$lbcc = "";
						echo "<script type='text/javascript'>
							UserMail(\"".$txtEmail."\",\"".$lSubject."\",\"".$lbody."\",\"".$lcc."\",\"".$lbcc."\");
			            </script>"; 
			    //header("Location: UsersList.php");
			}
		}
		
		if(isset($_POST['btnUpdate'])) 
		{
			$txtUserName = $_POST['txtUserName'];
			$txtEmail = $_POST['txtEmail'];
			$txtMobile = $_POST['txtMobile'];
			$ddlStatus = trim($_POST['ddlStatus']);
			$lhfRoleId = $_POST['hfRoleId'];
			if($lhfRoleId == "")
			{
				$lhfRoleId = 1;
			}
			else
			{
				$lhfRoleId = $_POST['hfRoleId'];
			}
			$lCMaster->UpdateUser($UID,$txtEmail,$txtMobile,$txtUserName,$Date,$ipaddress,$ddlStatus,$lhfRoleId);
			header("Location: UsersList.php");
		}
		
		if(isset($_POST['btnCancel']))
		{
			header("Location: UsersList.php");
		}
		
		?>
		<div style="height: 10px;"></div>
		<div style="border:solid 1px navy;width:790px;margin:10px;text-align: left;">
        <?php
        if(isset($_GET['UID']))
		{
			echo'<div style="color: white;background-color: navy;width:790px;height: 30px;line-height: 30px;vertical-align: middle;">&nbsp;&nbsp;Update User Details</div>';
		}
		else
		{
			echo'<div style="color: white;background-color: navy;width:790px;height: 30px;line-height: 30px;vertical-align: middle;">&nbsp;&nbsp;Add New User</div>';
		}
        ?>
        	<input type="hidden" name="hfRoleId" id="hfRoleId" value="<?php echo $lhfRoleId;?>"/>
        	
        	<div style="height:10px;"></div>
        	
        	<div style="width:100px;display:inline-block;">&nbsp;&nbsp;User Name</div>
        	<div style="width:15px;font-weight:bold;display:inline-block;text-align: center;">:</div>
        	<div style="display:inline-block;">
        		<input type="text" name = "txtUserName" id = "txtUserName"  autocomplete="off" value = "<?php echo $txtUserName;?>" style="height: 25px;width:320px;"/>
        	</div>
        	<div style="display:inline-block;color:red;">
        		<label name = "lblUserName" id = "lblUserName"></label>
        	</div>
        	
        	<div style="height:10px;"></div>
        	
        	<div style="width:100px;display:inline-block;">&nbsp;&nbsp;Email Id</div>
        	<div style="width:15px;font-weight:bold;display:inline-block;text-align: center;">:</div>
        	<div style="display:inline-block;">
        		<input type="text" name = "txtEmail" id = "txtEmail"  autocomplete="off" value = "<?php echo $txtEmail;?>" style="height: 25px;width:320px;"/>
        	</div>
        	<div style="display:inline-block;color:red;">
        		<label name = "lblEmail" id = "lblEmail"></label>
        	</div>
        	
        	<div style="height:10px;"></div>
        	
        	<div style="width:100px;display:inline-block;">&nbsp;&nbsp;Mobile No</div>
        	<div style="width:15px;font-weight:bold;display:inline-block;text-align: center;">:</div>
        	<div style="display:inline-block;">
        		<input type="text" name = "txtMobile" id = "txtMobile"  autocomplete="off" maxlength="10" value = "<?php echo $txtMobile;?>" style="height: 25px;width:320px;"/>
        	</div>
        	<div style="display:inline-block;color:red;">
        		<label name = "lblMobile" id = "lblMobile"></label>
        	</div>
        	
        	<div style="height:10px;"></div>
        	
        	<div style="width:100px;display:inline-block;">&nbsp;&nbsp;Status</div>
        	<div style="width:15px;font-weight:bold;display:inline-block;text-align: center;">:</div>
        	<div style="display:inline-block;">
        	<select name="ddlStatus" id="ddlStatus" class="divText" style="width:150px;height:30px;font-size:14px;">
				<option value="1">Active</option>
				<option value="9">Inactive</option>
			</select>
        	</div>
        	<div style="display:inline-block;color:red;">
        		<label name = "lblStatus" id = "lblStatus"></label>
        	</div>
        	
        	<div style="height:10px;"></div>
        	
        	<div style="width:100px;display:inline-block;"></div>
        	<div style="width:15px;font-weight:bold;display:inline-block;text-align: center;"></div>
        	<div style="display:inline-block;">
        	<?php
	        	if($lhfRoleId == 2)
	        	{
					echo'<input type="checkbox" id = "chkrole" name="chkrole" checked onclick="return addrole();">Admin<br>';
				}
				else
				{
					echo'<input type="checkbox" id = "chkrole" name="chkrole"  onclick="return addrole();">Admin<br>';
				}
			?>
        		
        	</div>
        	
        	<?php
        		if($_GET['lerr'] == 1)
			    {
					echo "<label style='color:red;'>User already Exist</label>";
				}
        	?>
        	
        	<div style="height:10px;"></div>
        	<div style="width:100px;display:inline-block;"></div>
        	<div style="width:15px;font-weight:bold;display:inline-block;"></div>
        	<div style="display:inline-block;">
        	
        	<?php
        		if(isset($_GET['UID']))
				{
					echo'<input type = "submit" value = "Update" name = "btnUpdate" id = "btnUpdate" class="button" style = "width:80px;background-color: navy;border:solid 1px navy;" onclick = "return valUser();"/>';
				}
				else
				{
					echo'<input type = "submit" value = "Save" name = "btnSave" id = "btnSave" class="button" style = "width:80px;background-color: navy;border:solid 1px navy;" onclick = "return valUser();"/>';
				}
        	?>
        	
        	<input type = "submit" value = "Cancel" name = "btnCancel" id = "btnCancel" class="button" title="Cancel" style = "width:80px;background-color: navy;border:solid 1px navy;"/>
        	</div>
			<div style="height:10px;"></div>       
        </div>
	</div>
	</form>
</body>
</html>