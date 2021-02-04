# interview-time-tracking
A homework task from a web development company.

The task consists of writing a little time tracking application.

This repository stores the API code for the above mentioned application.

Please find the instructions for the frontend part of the application below:
https://github.com/zexa/interview-time-tracker-frontend

## Requirements
* docker-compose (tested with version 1.27.4)
* docker (tested with version 19.03.13-ce, build 4484c46d9d)

## Usage
```
git clone git@github.com:zexa/interview-time-tracker.git
# git clone https://github.com/zexa/interview-time-tracker.git
cd interview-time-tracker
docker-compose -f docker/docker-compose.yml up --build -d
docker-compose -f docker/docker-compose.yml exec fpm /bin/sh
composer install
bin/console doctrine:migrations:migrate
mkdir -p config/jwt
chown -R www-data:www-data config/jwt
chmod -R 644 config/jwt/*
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout # use password pass
```

Afterwards, the application should be reachable via http://localhost:8080

## Thoughts

### Code smells
Jau sekmadienis, o priduoti tureciau antradieni.

Nezinau ar spesiu uzsiimti taisymu, todel noriu ~parodyti revieweriu, kad 
suprantu, kad yra spragu~ uzbegti uz akiu revieweriu ir taip issaugoti kuo 
daugiau balu kaip kandidatas. :smile:

* Controlleriai daro normalizacija ir verslo logika
  * Payseroje gauciau velniu uz manageriu nenaudojima, o siai uzduociai 
    nusprendziau, kad man svarbiau atitikti uzduoties reikalavimus.
  * Maniau, kad dar spesiu pasirefactorinti, bet laiko kaip ir nebera.
* Truksta testu!
* Email verifikacija, jeigu appas toliau butu tobulinamas
    * Priesingu atveju butu galima parasyti bota, kuris spamintu naujus acc
* Tasku kurimo limitacijos. DDos reasons.
* Yra galimybe, kad labai dideli reportai gali apkrauti php procesa.
    * Reiktu susikelt i RabbitMQ eiles.
    * Turbut ir limituoti kiek reportu zmogus gali kurti per diena.
* Reportu salinima po kiek laiko.
* Normalizavimas/Serializavimas nepatogus.
    * Gal reiktu pabandyt payseros normalizavimo liba?
* Noretusi atsikratyt PublicTask ir visur naudoti tik Task.
    * Nujauciu, kad toks approachas turetu savu problemu.
* Komentarai PublicTask modelyje turetu tureti savo PublicComment 
  reprezentacija.
    * Arba kaip auksciau mineta del Task, pradeti mastyti kaip atsikratyti 
      viesu reprezentaciju ir visur naudoti savo vidine.
* Modeliai grupuojasi su entyciais. Gal reiktu pradet skirt i atskira 
  kategorija?
* Datetime/Dateinterval normalizavimas kai kur kartojasi. Not very DRY.

### "Scalability"
Uzduotyje labai abstrakciai uzsiminima apie scalability. Mano interpretacija
buvo labiau, kad appsas turetu buti extendable. Nezinau ar cia skyrybos klaida,
ar as siaip panikuoju.

As suprantu "Scalability" labiau, kaip uztikrinima, kad servisas gerai atlaiko
tukstancius vartotoju, tasku, reportu generavimo, etc. Tokius dalykus 
dazniausiai uztikrinu mastydamas apie "Horizontal scaling". Horizontal scaling
yra idomi tema, todel naudoju fpm'a bei mastau kur butu protinga panaudoti 
eiles.

Is scalability puses tai siuo metu apsas turi spragu, kaip ir mineta auksciau
"code smells" kategorijoje. Zmones gali ddosint appsa su botu pagalba 
spaminant accountus, taskus, reportus.

Kas liecia extensibility, tai numatyta keleta extension pointu. Automatinis
failu trinimas, reportu generavimas per eiles (rabbitmq), email patvirtinimas,
loginai per google ir kitas sistemas palaikancias JWT. Zodziu "The sky is the 
limit".

Nekalbu apie menkus patobulinimus, tokius kaip pdf'o grazinima ar kitu reportu
patobulinimus, ten viskas mano nuomuone dabar paprasta ir patobulinti taipat
butu nesunku.
