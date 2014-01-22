
<h3 style="color:#3276B1;">View By Category</h3>

<div role="navigation" id="sidebar" >
    <ul class="nav nav-list accordion" id="sidenav-accordian" style="padding-bottom:10px;">
    <li>
        <div class="accordion-heading" style="padding-bottom:5px;">
        <a data-toggle="collapse" class="btn btn-default"  style="width:100%;" role="button" data-target="#accordion-heading-cars"><span class="nav-header-primary">Autos(29)</span></a>
        </div>
        
        <ul class="nav nav-list collapse" id="accordion-heading-cars">
            <a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?x=<?php echo @$_GET['x']; ?>" title="Title">Cars(25)</a>
            <a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?x=<?php echo @$_GET['x']; ?>" title="Title">ATVS(4)</a>
           
        </ul>
       
    </li>    
    <li>
        <div class="accordion-heading" style="padding-bottom:5px;">
        <a data-toggle="collapse"class="btn btn-default"  style="width:100%;" role="button" data-target="#accordion-heading-animal"><span class="nav-header-primary">Animals(29)</span></a>
        </div>
        
        <ul class="nav nav-list collapse" id="accordion-heading-animal">
           <a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?x=<?php echo @$_GET['x']; ?>" title="Title">Cats(25)</a>
           <a class="btn btn-primary" role="button" style="width:100%;margin-bottom:2px;" href="category.php?x=<?php echo @$_GET['x']; ?>" title="Title">Dogs(4)</a>
        </ul>
       
    </li>
    </ul>
</div>

