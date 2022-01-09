<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>
<body style="background-color:grey;">
    <div class="container ">
        <div class ="row">
            <div class = "col">
            <div class="d-flex justify-content-center align-items-center">
                <div class = "card mt-5 bg-dark " style="width: 600px;">
                    <div class = "card-body">
                       <nav class="nav justify-content-center">
                            <a href = "preqinput.php" class ="navlink">Prequisite Search</a>
                       </nav>
                       <nav class="nav justify-content-center">
                            <a href = "pbasedpreqinput.php" class ="navlink">Program Based Prequisite Search</a>
                       </nav>
                       
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="col">
            <div class="d-flex justify-content-center align-items-center">
            <div class = "card mt-5 bg-dark" style = "width: 600px; height: 600px;">
            <h1 class="card-header text-center text-primary " >Info</h1>
            <p class="card-body text-primary ">This project was designed by 4 Sophomore students as a PROJ 201 project. Although we trust our programming skills, we highly recommend you that double check what you see here.</br>
            </br>Here is a quick quide: </br></br> - Enter the course that you want to know its prequisites. </br> - You will see the prequisite chain of it. </br>
            - You may see "and" or "or" nodes which connects some courses to another course. Let's say x,y and z has an edge with an "and" operator and this operator
            has an edge with t. It should be read like this: You need to take x and y and z in order to unlock t. </br>
            - Note that nodes are most of the time clickable which directs you to the course catalog information of that course. Use that if you have any hesitation.</br>
            - Also there may be logical issues with the graph sometimes, which is not due to our implementation but the info that course catalog contains.
            

            
            
            
            </p>
            </div>
            </div>
            </div>
            
            
            </div>
            
            
               
        

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>