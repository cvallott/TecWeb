<header>
    <img src="../../assets/logo/logo.jpg" alt="Logo Non solo pizza">
    <nav id="menu">
        <ul>
			<?php
				$currentpage = basename($_SERVER['PHP_SELF']);
				if($currentpage == 'index.php') {
					echo '<li id="currentLink" lang="en">Home</li>';
				}else{
                    echo '<li><a href="../index.php"><span lang="en">Home</span></a></li>';
				}
				if($currentpage == 'chi-siamo.php') {
                    echo '<li id="currentLink">Chi siamo</a></li>';
                }else{
                    echo '<li><a href="../chi-siamo.php">Chi siamo</a></li>';
                }
				if($currentpage == 'menu-prenota.php') {
                    echo '<li id="currentLink">Menù-Prenota</a></li>';
                }else{
                    echo '<li><a href="../menu-prenota.php">Menù-Prenota</a></li>';
                }
			?>
             <!-- LANG DENTRO SPAN VICINO AD HOME O VA BENE COSI? -->
            <li><a href="../menu.html">Menù-Prenota</a></li>
            <li><a href="../login.html">Area riservata</a></li>
            <!-- INTANTO LO LASCIAMO COMMENTATO SE DOVESSE SERVIRE -->
            <!-- <div id="dropdown">
                <li><button id="droplink">Area riservata</button></li>
                <div id="dropdown-content">
                    <li><a href="login.html"><span lang=en>Login</span></a></li>
                    <li><a href="registrati.html">Registrati</a></li>
                </div>
            </div> -->
        </ul>
    </nav>
</header>