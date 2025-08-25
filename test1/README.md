# Sistema de Gestión de Vacunas

Sistema web completo para la gestión de vacunas e información de infantes y sus responsables, desarrollado en PHP con patrón MVC y frontend en HTML/CSS/Bootstrap.

## 🏗️ Estructura del Proyecto

```
test1/
├── config/
│   └── database.php          # Configuración de la base de datos
├── controllers/               # Controladores (Lógica de negocio)
│   ├── ResponsableController.php
│   ├── InfanteController.php
│   ├── VacunaController.php
│   └── CitaController.php
├── models/                    # Modelos (Acceso a datos)
│   ├── Responsable.php
│   ├── Infante.php
│   ├── Vacuna.php
│   └── Cita.php
├── views/                     # Vistas (Interfaz de usuario)
│   └── layout/
│       ├── header.php
│       └── footer.php
├── dbs/                       # Base de datos
│   └── bd_vac.sql
├── index.php                  # Dashboard principal
├── responsables.php           # Gestión de responsables
├── infantes.php              # Gestión de infantes
├── vacunas.php               # Gestión de vacunas
├── citas.php                 # Gestión de citas
└── README.md                 # Este archivo
```

## 🚀 Características

- **Dashboard completo** con estadísticas y resumen de datos
- **Gestión de Responsables**: CRUD completo para responsables de infantes
- **Gestión de Infantes**: CRUD completo con cálculo automático de edad
- **Gestión de Vacunas**: CRUD completo para catálogo de vacunas
- **Gestión de Citas**: Programación y seguimiento de vacunación
- **Interfaz moderna** con Bootstrap 5 y diseño responsive
- **Validaciones** en frontend y backend
- **Patrón MVC** para código organizado y mantenible

## 📋 Requisitos del Sistema

- **Servidor web**: Apache/Nginx con PHP 7.4 o superior
- **Base de datos**: MySQL 5.7+ o MariaDB 10.2+
- **PHP**: 7.4+ con extensiones PDO y MySQL
- **Navegador**: Moderno con soporte para JavaScript ES6+

## ⚙️ Instalación

### 1. Configuración del Servidor
- Coloca el proyecto en tu directorio web (ej: `htdocs/` en XAMPP)
- Asegúrate de que PHP esté habilitado en tu servidor

### 2. Base de Datos
```sql
-- Crear la base de datos
CREATE DATABASE bd_vac CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Importar la estructura y datos
mysql -u root -p bd_vac < dbs/bd_vac.sql
```

### 3. Configuración de Conexión
Edita `config/database.php` con tus credenciales:
```php
private $host = 'localhost';        // Tu servidor MySQL
private $db_name = 'bd_vac';       // Nombre de la base de datos
private $username = 'root';         // Usuario MySQL
private $password = '';             // Contraseña MySQL
```

### 4. Permisos
- Asegúrate de que el servidor web tenga permisos de lectura en el directorio
- Para producción, configura permisos apropiados de seguridad

## 🎯 Uso del Sistema

### Dashboard Principal (`index.php`)
- Vista general de estadísticas
- Acceso rápido a todas las funcionalidades
- Resumen de citas e infantes recientes

### Gestión de Responsables (`responsables.php`)
- Listar todos los responsables
- Crear nuevos responsables
- Editar información existente
- Eliminar responsables (con confirmación)

### Gestión de Infantes (`infantes.php`)
- Listar todos los infantes con información del responsable
- Crear nuevos infantes
- Editar información existente
- Cálculo automático de edad
- Eliminar infantes (con confirmación)

### Gestión de Vacunas (`vacunas.php`)
- Listar todas las vacunas disponibles
- Crear nuevas vacunas
- Editar información existente
- Eliminar vacunas (con confirmación)

### Gestión de Citas (`citas.php`)
- Listar todas las citas programadas
- Crear nuevas citas de vacunación
- Editar citas existentes
- Eliminar citas (con confirmación)
- Validación de fechas

## 🔧 Funcionalidades Técnicas

### Seguridad
- **Prevención SQL Injection**: Uso de PDO con prepared statements
- **XSS Protection**: Escape de HTML en todas las salidas
- **Validación de datos**: Frontend y backend
- **Confirmaciones**: Para operaciones destructivas

### Validaciones
- Campos obligatorios marcados con *
- Validación de fechas (no fechas pasadas para citas)
- Validación de edad para infantes
- Verificación de relaciones entre entidades

### Interfaz de Usuario
- **Responsive Design**: Funciona en dispositivos móviles y desktop
- **Bootstrap 5**: Framework CSS moderno y accesible
- **Iconos**: Bootstrap Icons para mejor UX
- **Alertas**: Sistema de notificaciones para el usuario
- **Navegación**: Sidebar con menú contextual

## 📱 Características Responsive

- **Sidebar colapsable** en dispositivos móviles
- **Tablas responsive** con scroll horizontal
- **Formularios adaptativos** según el tamaño de pantalla
- **Botones y controles** optimizados para touch

## 🚨 Consideraciones de Seguridad

### Para Producción
1. **Cambiar credenciales** de base de datos por defecto
2. **Configurar HTTPS** para transmisión segura de datos
3. **Implementar autenticación** de usuarios
4. **Configurar firewall** y restricciones de acceso
5. **Hacer backup regular** de la base de datos
6. **Monitorear logs** del servidor

### Validaciones Adicionales Recomendadas
- Implementar CAPTCHA para formularios
- Agregar rate limiting para prevenir spam
- Implementar auditoría de cambios
- Agregar validación de archivos si se implementa subida

## 🐛 Solución de Problemas

### Error de Conexión a Base de Datos
- Verificar que MySQL esté ejecutándose
- Confirmar credenciales en `config/database.php`
- Verificar que la base de datos `bd_vac` exista

### Páginas en Blanco
- Verificar logs de error de PHP
- Confirmar que todas las dependencias estén incluidas
- Verificar permisos de archivos

### Problemas de Estilo
- Verificar conexión a internet para CDN de Bootstrap
- Limpiar caché del navegador
- Verificar consola del navegador para errores JavaScript

## 🔄 Actualizaciones Futuras

### Funcionalidades Planificadas
- [ ] Sistema de autenticación y roles
- [ ] Reportes y estadísticas avanzadas
- [ ] Calendario de vacunación
- [ ] Notificaciones por email/SMS
- [ ] API REST para integración con otros sistemas
- [ ] Backup automático de base de datos
- [ ] Logs de auditoría completos

### Mejoras Técnicas
- [ ] Implementar Composer para dependencias
- [ ] Agregar tests unitarios
- [ ] Implementar caché de consultas
- [ ] Optimización de consultas SQL
- [ ] Implementar paginación para listas grandes

## 📞 Soporte

Para soporte técnico o reportar problemas:
1. Revisar la documentación
2. Verificar logs del servidor
3. Consultar la consola del navegador
4. Verificar la configuración de la base de datos

## 📄 Licencia

Este proyecto está desarrollado para fines educativos y de demostración. Puede ser modificado y distribuido libremente.

---

**Desarrollado con ❤️ usando PHP, MySQL, HTML, CSS y Bootstrap**
