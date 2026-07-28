# Plataforma-HealthTracker
Trabajo final de Proyecto de software 2025
Se utilizara CodeIgniter y AdminLte

----
# Enlaces
Pagina principal: http://localhost/Plataforma-HealthTracker/public/

# Estructura del proyecto
```
app/
 ├─ Controllers/     # Organizados por rol: Admin, Doctor, Paciente
 ├─ Models/          # Acceso a datos y validaciones
 ├─ Views/           # Vistas por rol + templates comunes (header, sidebar, footer)
 ├─ Filters/         # AuthFilter: autenticación y autorización por rol
 └─ Config/          # Rutas, base de datos, filtros
db_script/
 └─ tpfinal_ps_bd.sql  # Script de creación de la base de datos
```


# Credenciales
Rol: Administrador
email: admin@demo.com
pass: password

Rol: Medico
email: rfernandez@demo.com
pass: password

Rol: Paciente
email:vsuarez@demo.com
pass: password
