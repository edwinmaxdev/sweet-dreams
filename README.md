# sweet-dreams
Sistema web de gestión de pedidos para una pastelería, desarrollado con PHP, MySQL y patrón MVC.

## Tecnologías utilizadas
- PHP
- MySQL
- HTML, CSS, JavaScript
- Patrón MVC

## Requisitos
- XAMPP (PHP 8+ y MySQL)
- MySQL Workbench (opcional)

## Instalación
1. Clona el repositorio
2. Copia `config/database.example.php` y renómbralo a `config/database.php`
3. Rellena tus credenciales de conexión en `database.php`
4. Importa el archivo `database/database.sql` en tu gestor de base de datos
5. Levanta XAMPP y abre `http://localhost/sweet-dreams/public/`

## Roles del sistema
- **Admin**: gestión completa de usuarios, productos y pedidos
- **Empleado**: gestión de pedidos asignados
- **Cliente**: catálogo de productos y sus propios pedidos

