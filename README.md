
    1. Crear estructura para proyecto Symfony (5 min)
    2. Crear entidad TODO con los siguintes campos: (5 min)
        ◦ nombre
        ◦ fecha creación (automática)
        ◦ fecha tope
        ◦ estado
    3. Crear interfaz para añadir elementos a la lista y visualizar el listado. (30min)
    4. Posibilidad de marcar como realizada la tarea y diferenciar visualmente de las pendientes. (1 hora)
    7. Añadir login y autenticar la aplicación. Crear un rol admin para gestionar todas las listas. Un rol usuario accederá únicamente a su lista. (30 min)
    8. Añadir para un usuario con rol admin la posibilidad de modificar el dueño de la lista, pudiendo un usuario tener varias listas de TODO. (1 hora)
    9. (Opcional) Añadir creación de usuarios. (5 min)
    10. Un admin puede visualizar las listas TODO del resto de usuarios, pero no modificar sus elementos. (30 min)

Se ha creado una migración para crear un usuario administrador cuyos datos de inicio de sesión son:
email: myphpisland@gmail.com
contraseña: 123456
Cuando un usuario se registra, se crea en el sistema una lista vinculada a ese usuario, donde se irán guardando
los todos que el usuario cree. La página de registro de usuarios solo está disponible para el usuario administrador,
puesto que por defecto todos los usuarios creados solo tienen el role de usuario. 


