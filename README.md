# foodieangelsapi
Parte back de la aplicación

Para instalar:

Descargamos la aplicación

En el archivo ***.env*** editamos la base de datos y ponemos la que hemos creado, así como el usuario y la contraseña 


**DATABASE_HOST**=#La conexión

**DATABASE_USER**=#El usuario a conectarse

**DATABASE_PASSWORD**=#La contraseña con la que se conecta

**DATABASE_SCHEMA**=#La base de datos a la que vamos a acceder



Con el composer, mysql y php+apache instalado ejecutamos los siguientes comandos
> **composer install**

> **php bin/console make:migration**

> **php bin/console doctrine:migrations:migrate**

>**php bin/console doctrine:database:import sql/***

Luego vaya al siguiente archivo y edite los datos de nombre a su gusto *src/DataFixtures/UserFixture.php*

Una vez editado ejecute lo siguiente

> **php bin/console doctrine:fixtures:load**

Una vez que haya hecho todo configure el apache para que apunte a la ruta ./public/.

Dentro encontrará el fichero .htacces que debería configurar a su medida 

En nuestro sistema usamos la ruta https://RUTA/api -> para el api

*RewriteRule (.+) /**api**/index.php?p=$1 [QSA,L]*
