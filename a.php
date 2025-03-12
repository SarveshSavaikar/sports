<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <div class="container">
    <header>
        <h1>Welcome to GOA SCORES</h1>
    </header>
    <nav>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
        <a href="club-kmmdetails.php">View Club Details</a>
        <a href="Coaches.php">Coaches</a>
        <a href="Physiotherapist.php">Physiotherapist</a>
        <a href="Nutritionist.php">Nutritionist</a>
    </nav>
        <div id="slide">
            
            <div class="item" style="background-image: url(https://th.bing.com/th/id/OIP.BZI0IGxj283HZcPkKOFGZgHaJQ?w=501&h=626&rs=1&pid=ImgDetMain);">
                <div class="content">
                    <div class="name">Goa Scores</div>
                    <div class="des">One Platform For Sports Lovers!!</div>
                </div>
            </div>
            <div class="item" style="background-image: url(https://th.bing.com/th/id/OIP.3E0SDQOnb3aWYALOP8jh2gHaE8?rs=1&pid=ImgDetMain);">
                <div class="content">
                    <div class="name">Engage with Coaches</div>
                    <div class="des">"Train with the best! Connect with expert coaches for personalized guidance and take your skills to the next level. Your journey to greatness begins here!"</div>
            
                </div>
            </div>
            <div class="item" style="background-image: url(https://th.bing.com/th/id/OIP.rjfloBsnZAhoQmMudQJ4KAHaFF?rs=1&pid=ImgDetMain);">
                <div class="content">
                    <div class="name">Book Appointments with Physiotherapist</div>
                    <div class="des">"Recover stronger! Book appointments with expert physiotherapists and get tailored care to keep you in peak condition. Your health, our priority!"</div>
                    
                </div>
            </div>
            <div class="item" style="background-image: url(https://thumbs.dreamstime.com/b/right-nutrition-concept-happy-african-american-nutritionist-showing-weekly-diet-plan-sitting-desk-plates-fruits-240189499.jpg);">
                <div class="content">
                    <div class="name">Book Appointments with Nutritionists</div>
                    <div class="des">"Fuel your performance! Book appointments with top nutritionists and get personalized plans to stay healthy and energized. Your wellness journey starts here!"</div>
                    
                </div>
            </div>
            <div class="item" style="background-image: url(https://th.bing.com/th/id/OIP.30_cwDjkK7MjHtSEkiVztwHaE8?rs=1&pid=ImgDetMain);">
                <div class="content">
                    <div class="name">Book Grounds</div>
                    <div class="des">"Secure your spot and play your way! Book sports grounds effortlessly and enjoy the game without the hassle. Your perfect playtime starts here!"</div>
                   
                </div>
            </div>
            <div class="item" style="background-image: url(https://th.bing.com/th/id/OIP.W9A5GXKGkQXNz29SCCfCFQHaFL?rs=1&pid=ImgDetMain);">
                <div class="content">
                    <div class="name">Buy Equipments</div>
                    <div class="des">"Level up your game with trendy sports gear! From top-notch equipment to the latest styles, we’ve got everything you need to play hard and look good. Get ready to dominate the field in style!"</div>
                    
                </div>
            </div>
        </div>
        <div class="buttons">
            <button id="prev"><i class="fa-solid fa-angle-left"></i></button>
            <button id="next"><i class="fa-solid fa-angle-right"></i></button>
        </div>
    </div>

    <script src="scriptt.js"></script>
</body>
</html>
  
<style>
body{
    background-color: #eaeaea;
    overflow: hidden;
}
.container{
    position: absolute;
    left:50%;
    top:50%;
    transform: translate(-50%,-50%);
    width:1000px;
    height:600px;
    padding:50px;
    background-color: #f5f5f5;
    box-shadow: 0 30px 50px #dbdbdb;
}
#slide{
    width:max-content;
    margin-top:50px;
}
.item{
    width:200px;
    height:300px;
    background-position: 50% 50%;
    display: inline-block;
    transition: 0.5s;
    background-size: cover;
    position: absolute;
    z-index: 1;
    top:50%;
    transform: translate(0,-50%);
    border-radius: 20px;
    box-shadow:  0 30px 50px #505050;
}
.item:nth-child(1),
.item:nth-child(2){
    left:0;
    top:0;
    transform: translate(0,0);
    border-radius: 0;
    width:100%;
    height:100%;
    box-shadow: none;
}
.item:nth-child(3){
    left:50%;
}
.item:nth-child(4){
    left:calc(50% + 220px);
}
.item:nth-child(5){
    left:calc(50% + 440px);
}
.item:nth-child(n+6){
    left:calc(50% + 660px);
    opacity: 0;
}
.item .content{
    position: absolute;
    top:50%;
    left:100px;
    width:300px;
    text-align: left;
    padding:0;
    color:#eee;
    transform: translate(0,-50%);
    display: none;
    font-family: system-ui;
}
.item:nth-child(2) .content{
    display: block;
    z-index: 11111;
}
.item .name{
    font-size: 40px;
    font-weight: bold;
    opacity: 0;
    animation:showcontent 1s ease-in-out 1 forwards
}
.item .des{
    margin:20px 0;
    opacity: 0;
    animation:showcontent 1s ease-in-out 0.3s 1 forwards
}
.item button{
    padding:10px 20px;
    border:none;
    opacity: 0;
    animation:showcontent 1s ease-in-out 0.6s 1 forwards
}
@keyframes showcontent{
    from{
        opacity: 0;
        transform: translate(0,100px);
        filter:blur(33px);
    }to{
        opacity: 1;
        transform: translate(0,0);
        filter:blur(0);
    }
}
.buttons{
    position: absolute;
    bottom:30px;
    z-index: 222222;
    text-align: center;
    width:100%;
}
.buttons button{
    width:50px;
    height:50px;
    border-radius: 50%;
    border:1px solid #555;
    transition: 0.5s;
}.buttons button:hover{
    background-color: #bac383;
}</style>