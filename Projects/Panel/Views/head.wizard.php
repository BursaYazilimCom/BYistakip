<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="BURSA YAZILIM">
    <title>{{AyarModel::defaultAyarlar('siteAdi')}}</title>
    <link rel="apple-touch-icon" href="images/ico/logo-mini.png">
    <link rel="shortcut icon" type="image/x-icon" href="images/ico/logo-mini.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="vendors/css/extensions/toastr.min.css">
<link rel="stylesheet" type="text/css" href="vendors/css/forms/select/select2.min.css">
    <!-- END: Vendor CSS-->


    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="css/colors.css">
    <link rel="stylesheet" type="text/css" href="css/components.css">
    <link rel="stylesheet" type="text/css" href="css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="css/themes/semi-dark-layout.css">


    <link rel="stylesheet" type="text/css" href="css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="css/pages/authentication.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="css/core/menu/menu-types/horizontal-menu.css">
    <link rel="stylesheet" type="text/css" href="css/pages/dashboard-ecommerce.css">
    <link rel="stylesheet" type="text/css" href="css/plugins/charts/chart-apex.css">
    <link rel="stylesheet" type="text/css" href="css/plugins/extensions/ext-component-toastr.css">
        <link rel="stylesheet" type="text/css" href="css/pages/modal-create-app.css">
        <link rel="stylesheet" type="text/css" href="vendors/css/pickers/flatpickr/flatpickr.min.css">



    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END: Custom CSS-->
    @if(CURRENT_CONTROLLER=='Login')

        <style>
            * {
  box-sizing: border-box;
}

html,
body {
  height: 100%;
}

body {
  display: grid;
  place-items: center;
  margin: 0;
  padding: 0 32px;
  background: #f5f5f5;
  animation: rotate 6s infinite alternate linear;
}

@media (width >= 500px) {
  body {
    padding: 0;
  }
}

.background {
  position: fixed;
  top: -50vmin;
  left: -50vmin;
  width: 100vmin;
  height: 100vmin;
  border-radius: 47% 53% 61% 39% / 45% 51% 49% 55%;
  background: #65c8ff;
}

.background::after {
  content: "";
  position: inherit;
  right: -50vmin;
  bottom: -55vmin;
  width: inherit;
  height: inherit;
  border-radius: inherit;
  background: #143d81;
}

.card {
  overflow: hidden;
  position: relative;
  z-index: 3;
  width: 100%;
  margin: 0 20px;
  padding: 170px 30px 54px;
  border-radius: 1.25rem;
  background: #fff;
  text-align: center;
  box-shadow: 0 100px 100px rgb(0 0 0 / 10%);
}

.card::before {
  content: "";
  position: absolute;
  top: -880px;
  left: 50%;
  translate: -50% 0;
  width: 1000px;
  height: 1000px;
  border-radius: 50%;
  background: #216ce7;
}

@media (width >= 500px) {
  .card {
    margin: 0;
    width: 360px;
  }
}

.card .logo {
  position: absolute;
  top: 30px;
  left: 50%;
  translate: -50% 0;
  width: 260px;
}

.card > h2 {
  font-size: 22px;
  font-weight: bold;
  margin: 0 0 30px;
  color: #0F1114;
}

.form {
  margin: 0 0 36px;
  display: grid;
  gap: 16px;
}

.form > input,
.form > button {
  width: 100%;
  height: 56px;
  border-radius: 28px;
}

.form > input {
  border: 2px solid #ebebeb;
  font-family: inherit;
  font-size: 16px;
  padding: 0 24px;
  color: #11274c;
}

.form > input::placeholder {
  color: #cac8c8;
}

.form > button {
  cursor: pointer;
  width: 100%;
  height: 56px;
  padding: 0 16px;
  background: #216ce7;
  color: #f9f9f9;
  border: 0;
  font-family: inherit;
  font-size: 20px;
  font-weight: 600;
  text-align: center;
  letter-spacing: 2px;
  transition: all 0.375s;
}

.card > footers {
  color: #a1a1a1;
}

.card > footers > a {
  color: #216ce7;
}

        </style>
    @endif