# core
orion system core base
Desarrollar un sistema base, desde donde se completara la programación según el sistema de gestión requerido. Donde se implementaran los archivos necesarios para el manejo de gestión por modulos, con acceso seguro mediante email y clave, con permisos por usuarios , módulos e ítems, controlados desde un sector administrativo
Este sistema base contara con un sector para facilitar la creación de los CRUDs por parte del programador, utilizando  la siguiente metodologia: se envia a un php las variables y parámetros de la consulta, el php devuelve un json para que un js  devuelva el codigo html resultante, evitando tener que repetir codigo por cada CRUD
La interfaz debe ser UX/UI responsive, con bootstrap 5.3, php8 JS, mysql/mariaDB