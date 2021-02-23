### Veb aplikacija za anketiranje studenata 

Povezano sa projektom: https://github.com/RAFSoftLab/studentske-ankete-init-and-reports 

#### Okruženje

Testirano na XAMPP verzija 7.1.30-0, Baza: 10.3.15-MariaDB, OS:Ubuntu 18.04.3 LTS. 

#### Uputstvo za pokretanje

U fajlu `database.php` upisati podatke za konekciju na bazu
 
U fajlu `questions.php` u liniji 71 podesiti u sql-u id pitanja koja će se koristiti u anketiranju (TODO: popraviti da se čita iz neke promenljive), ovo se koristi ako se menjaju pitanja za anketu, pa čuvamo i ona stara u bazi, ali ih ne koristimo u anketama, inače može da se promeni upit da se uzimaju sva pitanja iz baze.

Ova verzija se koristi za testiranje i preskače se logovanje preko gmail-a, u fajlu `survey.php` u liniji 80 postaviti email studenta za proveru da li se dobro selektuju predmeti za anketiranje. Za korišćenje uz gmail login odkomentarisati od linije 17 do linije 37, a liniju 80 zakomentarisati. 
