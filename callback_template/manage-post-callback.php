<style>
    /* Styles for the booklet container */
    .booklet {
      width: 1000px;
      margin: 50px auto 0;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: left; /* Aligns content to left */
    }
    /* Styles for the page content */
    .page {
      margin-top: 20px; /* Adds a little margin */
    }
    /* Styles for the instruction images */
    .instruction-image {
      display: block;
      margin: 20px auto;
      max-width: 100%;
    }
    /* Styles for the navigation links */
    .nav {
        text-align: center;
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    .nav a {
      display: inline-block;
      padding: 5px 10px;
      background-color: #eee;
      color: #333;
      text-decoration: none;
      margin-right: 10px;
      border-radius: 5px;
    }
    .nav a.active {
      background-color: #333;
      color: #fff;
    }
    .instruction-image {
        margin-left: 10px;
        width: 75%;
    }
  </style>
</head>
<body>
  <!-- Booklet container -->
  <div class="booklet">

    <!-- First page content -->
    <div class="page" id="page1">
      <h1>Creating a Facebook App</h1>
      <p>Here's how to create a Facebook app:</p>
      <ol>
        <li>Go to <a href="https://developers.facebook.com/apps/">https://developers.facebook.com/apps/</a></li>
        <img src="<?php echo plugins_url( 'img/createapp.jpg', __FILE__ ); ?>" alt="Screenshot of the Facebook Developer Page" class="instruction-image">
        <li>Click on "Create App"</li>
        <li>Choose a platform for your app</li>
        <img src="<?php echo plugins_url( 'img/apptype.png', __FILE__ ); ?>" alt="Screenshot of the App Type Page" class="instruction-image">
        <li>Fill out the required information for your app</li>
        <img src="<?php echo plugins_url( 'img/appinfo.png', __FILE__ ); ?>" alt="Screenshot of the App Info Page" class="instruction-image">
        <li>Submit your app for review (if required)</li>
      </ol>
    </div>

    <div class="page" id="page3" style="display:none">
        <h1>Get your app ID and secret key</h1>
        <p>To use the Facebook API and integrate Facebook Login into your website, you will need to create a Facebook app and obtain an App ID and App Secret. Here's how:</p>
        <ol>
            <li>Click Settings & choose Basic Setting on the menu sidebar.</li>
            <img src="<?php echo plugins_url( 'img/sidebar.png', __FILE__ ); ?>" alt="Screenshot of the Sidebar" class="instruction-image">
            <li>Your App ID is displayed at the top of the page.</li>
            <img src="<?php echo plugins_url( 'img/basicsetting.png', __FILE__ ); ?>" alt="Screenshot of the Basic Setting" class="instruction-image">
            <li>To obtain your App Secret, click on "Show" next to "App Secret". You will be prompted to enter your Facebook password. Once you've entered your password, your App Secret will be displayed.</li>
            <li>Paste your Facebook App ID & App Secret in the Set Up Page, then click Save Changes.</li>
            <img src="<?php echo plugins_url( 'img/setupsecret.png', __FILE__ ); ?>" alt="Screenshot of the Set Up" class="instruction-image">
        </ol>
    </div>

    <div class="page" id="page3" style="display:none">
        <h1>Link Page to Database</h1>
        <p>After the application status shows connected, continue to connect your account and page.</p>
        <ol>
            <li>Click login under My Profile. You will be directed to a facebook login page.</li>
            <img src="<?php echo plugins_url( 'img/pagelogin.png', __FILE__ ); ?>" alt="Screenshot of the Page Login" class="instruction-image">
        </ol>
    </div>

  </div> <!-- End of booklet container -->

  <!-- Navigation links -->
  <div class="nav">
    <a href="#page1" class="active">1</a>
    <a href="#page2">2</a>
    <a href="#page3">3</a>
  </div>

  <!-- JavaScript for page navigation -->
  <script>
    const pages = document.querySelectorAll('.page');
    const navLinks = document.querySelectorAll('.nav a');
    for (let i = 0; i < navLinks.length; i++) {
      navLinks[i].addEventListener('click', function() {
        for (let j = 0; j < pages.length; j++) {
          pages[j].style.display = 'none';
          navLinks[j].classList.remove('active');
        }
        pages[i].style.display = 'block';
        navLinks[i].classList.add('active');
      });
    }
  </script>