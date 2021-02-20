### Veb aplikacija za anketiranje studenata 

Povezano sa projektom: https://github.com/RAFSoftLab/studentske-ankete-init-and-reports 

U fajlu `database.php` upisati podatke za konekciju na bazu
 
U fajlu `questions.php` u liniji 71 podesiti u sql-u id pitanja koja će se koristiti u anketiranju (TODO: popraviti da se čita iz neke promenljive), ovo se koristi ako se menjaju pitanja za anketu, pa čuvamo i ona stara u bazi, ali ih ne koristimo u anketama, inače može da se promeni upit da se uzimaju sva pitanja iz baze
