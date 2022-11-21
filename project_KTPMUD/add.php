<?php
session_start();
include_once "config.php";
$connection = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
if ( !$connection ) {
    echo mysqli_error( $connection );
    throw new Exception( "Database cannot Connect" );
} else {
    $action = $_REQUEST['action'] ?? '';

    if ( 'addManager' == $action ) {
        $fname = $_REQUEST['fname'] ?? '';
        $lname = $_REQUEST['lname'] ?? '';
        $birthday = $_REQUEST['birthday'] ?? '';
        $address = $_REQUEST['address'] ?? '';
        $email = $_REQUEST['email'] ?? '';
        $phone = $_REQUEST['phone'] ?? '';
        $password = $_REQUEST['password'] ?? '';
        $gender = $_REQUEST['gender'] ?? '';
        $salary = $_REQUEST['salary'] ?? '';


        if ( $fname && $lname && $birthday && $address && $phone && $password && $gender && $salary ) {
            $hashPassword = password_hash( $password, PASSWORD_BCRYPT );
            $query = "INSERT INTO managers(fname,lname,birthday,address,email,phone,password,gender) VALUES ('{$fname}','$lname','$birthday','$address','$email','$phone','$hashPassword','$gender,'$salary')";
            mysqli_query( $connection, $query );
            header( "location:index.php?id=allManager" );
        }

    } elseif ( 'updateManager' == $action ) {
        $id = $_REQUEST['id'] ?? '';
        $fname = $_REQUEST['fname'] ?? '';
        $lname = $_REQUEST['lname'] ?? '';
        $birthday = $_REQUEST['birthday'] ?? '';
        $address = $_REQUEST['address'] ?? '';
        $email = $_REQUEST['email'] ?? '';
        $phone = $_REQUEST['phone'] ?? '';
        $gender = $_REQUEST['gender'] ?? '';
        $salary = $_REQUEST['salary'] ?? '';

        if ( $fname && $lname && $birthday &&$address && $phone && $gender && $salary ) {
            $query = "UPDATE managers SET fname='{$fname}', lname='{$lname}', birthday='{$birthday}', address = '{$address}', email='$email', phone='$phone', gender ='$gender', salary='$salary' WHERE id='{$id}'";
            mysqli_query( $connection, $query );
            header( "location:index.php?id=allManager" );
        }
    } elseif ( 'addPharmacist' == $action ) {
        $fname = $_REQUEST['fname'] ?? '';
        $lname = $_REQUEST['lname'] ?? '';
        $email = $_REQUEST['email'] ?? '';
        $phone = $_REQUEST['phone'] ?? '';
        $password = $_REQUEST['password'] ?? '';
        $gender = $_REQUEST['gender'] ?? '';

        if ( $fname && $lname && $lname && $phone && $password && $gender ) {
            $hashPassword = password_hash( $password, PASSWORD_BCRYPT );
            $query = "INSERT INTO pharmacists(fname,lname,email,phone,password,gender) VALUES ('{$fname}','$lname','$email','$phone','$hashPassword','$gender')";
            mysqli_query( $connection, $query );
            header( "location:index.php?id=allPharmacist" );
        }
    } elseif ( 'updatePharmacist' == $action ) {
        $id = $_REQUEST['id'] ?? '';
        $fname = $_REQUEST['fname'] ?? '';
        $lname = $_REQUEST['lname'] ?? '';
        $email = $_REQUEST['email'] ?? '';
        $phone = $_REQUEST['phone'] ?? '';
        $gender = $_REQUEST['gender'] ?? '';

        if ( $fname && $lname && $lname && $phone && $gender ) {
            $query = "UPDATE pharmacists SET fname='{$fname}', lname='{$lname}', email='$email', phone='$phone',gender = '$gender' WHERE id='{$id}'";
            mysqli_query( $connection, $query );
            header( "location:index.php?id=allPharmacist" );
        }
    } elseif ( 'addSalesman' == $action ) {
        $fname = $_REQUEST['fname'] ?? '';
        $lname = $_REQUEST['lname'] ?? '';
        $email = $_REQUEST['email'] ?? '';
        $phone = $_REQUEST['phone'] ?? '';
        $password = $_REQUEST['password'] ?? '';
        $gender = $_REQUEST['gender'] ?? '';

        if ( $fname && $lname && $lname && $phone && $password && $gender) {
            $hashPassword = password_hash( $password, PASSWORD_BCRYPT );
            $query = "INSERT INTO salesmans(fname,lname,email,phone,password,gender) VALUES ('{$fname}','$lname','$email','$phone','$hashPassword','$gender')";
            mysqli_query( $connection, $query );
            header( "location:index.php?id=allSalesman" );
        }
    } elseif ( 'updateSalesman' == $action ) {
        $id = $_REQUEST['id'] ?? '';
        $fname = $_REQUEST['fname'] ?? '';
        $lname = $_REQUEST['lname'] ?? '';
        $email = $_REQUEST['email'] ?? '';
        $phone = $_REQUEST['phone'] ?? '';
        $gender = $_REQUEST['gender'] ?? '';

        if ( $fname && $lname && $lname && $phone && $gender) {
            $query = "UPDATE salesmans SET fname='{$fname}', lname='{$lname}', email='$email', phone='$phone', '$gender' WHERE id='{$id}'";
            mysqli_query( $connection, $query );
            header( "location:index.php?id=allSalesman" );
        }
    } elseif ( 'updateProfile' == $action ) {

        $fname = $_REQUEST['fname'] ?? '';
        $lname = $_REQUEST['lname'] ?? '';
        $email = $_REQUEST['email'] ?? '';
        $phone = $_REQUEST['phone'] ?? '';
        $gender = $_REQUEST['gender'] ?? '';
        $oldPassword = $_REQUEST['oldPassword'] ?? '';
        $newPassword = $_REQUEST['newPassword'] ?? '';
        $sessionId = $_SESSION['id'] ?? '';
        $sessionRole = $_SESSION['role'] ?? '';
        $avatar = $_FILES['avatar']['name'] ?? "";

        if ( $fname && $lname && $email && $phone && $gender && $oldPassword && $newPassword ) {
            $query = "SELECT password,avatar FROM {$sessionRole}s WHERE id='$sessionId'";
            $result = mysqli_query( $connection, $query );

            if ( $data = mysqli_fetch_assoc( $result ) ) {
                $_password = $data['password'];
                $_avatar = $data['avatar'];
                $avatarName = '';
                if ( $_FILES['avatar']['name'] !== "" ) {
                    $allowType = array(
                        'image/png',
                        'image/jpg',
                        'image/jpeg'
                    );
                    if ( in_array( $_FILES['avatar']['type'], $allowType ) !== false ) {
                        $avatarName = $_FILES['avatar']['name'];
                        $avatarTmpName = $_FILES['avatar']['tmp_name'];
                        move_uploaded_file( $avatarTmpName, "assets/img/$avatar" );
                    } else {
                        header( "location:index.php?id=userProfileEdit&avatarError" );
                        return;
                    }
                } else {
                    $avatarName = $_avatar;
                }
                if ( password_verify( $oldPassword, $_password ) ) {
                    $hashPassword = password_hash( $newPassword, PASSWORD_BCRYPT );
                    $updateQuery = "UPDATE {$sessionRole}s SET fname='{$fname}', lname='{$lname}', email='{$email}', phone='{$phone}',gender='{$gender}', password='{$hashPassword}', avatar='{$avatarName}' WHERE id='{$sessionId}'";
                    mysqli_query( $connection, $updateQuery );

                    header( "location:index.php?id=userProfile" );
                }

            }

        } else {
            echo mysqli_error( $connection );
        }

    }

}
