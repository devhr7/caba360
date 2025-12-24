<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        /**
         * ========================================
         * Permisos
         * ========================================
         */

        /**
         *  TODO Config
         * */
        $config =  Permission::create(['name' => 'config.show','description'=>'Ver Modulo Configuracion']);

        //* Usuarios
        $config_user_ver =  Permission::create(['name' => 'config.user.show','description'=>'Ver Modulo Usuario']);
        $config_user_crear =  Permission::create(['name' => 'config.user.create','description'=>'Create Usuario']);
        $config_user_editar =  Permission::create(['name' => 'config.user.edit','description'=>'Editar Usuario']);
        $config_user_eliminar =  Permission::create(['name' => 'config.user.delete','description'=>'Eliminar Usuario']);
        $config_user_gestionrol =  Permission::create(['name' => 'config.user.gestionrol','description'=>'Gestion Rol a los Usuarios']);

        //* Rol
        $config_rol_ver =  Permission::create(['name' => 'config.rol.show','description'=>'Ver Modulo Rol']);
        $config_rol_crear =  Permission::create(['name' => 'config.rol.create','description'=>'Crear Rol']);
        $config_rol_editar =  Permission::create(['name' => 'config.rol.edit','description'=>'Editar Rol']);
        $config_rol_eliminar =  Permission::create(['name' => 'config.rol.delete','description'=>'Eliminar Rol']);
        $config_rol_gestionpermisos =  Permission::create(['name' => 'config.rol.gestionpermisos','description'=>'Gestion Permisos al rol']);

        // *Permisos
        $config_Permisos_ver =  Permission::create(['name' => 'config.permisos.show','description'=>'Ver Modulo Permisos']);
        $config_Permisos_crear =  Permission::create(['name' => 'config.permisos.create','description'=>'Crear Permisos']);
        $config_Permisos_editar =  Permission::create(['name' => 'config.permisos.edit','description'=>'Editar Permisos']);
        $config_Permisos_eliminar =  Permission::create(['name' => 'config.permisos.delete','description'=>'Eliminar Permisos']);



        /**
         *  TODO Parametros
         * */
        $parametros = Permission::create(['name' => 'param.show','description'=>'Ver Modulo Parametros']);
        // ? Empresa
        $parametros_empresa =  Permission::create(['name' => 'param.empresa.show','description'=>'Ver Modulo Empresa']);
        // Distrito
        $parametros_distrito_ver =  Permission::create(['name' => 'param.distrito.show','description'=>'Ver Distritos']);
        $parametros_distrito_crear =  Permission::create(['name' => 'param.distrito.create','description'=>'Crear Distrito Distritos']);
        $parametros_distrito_editar =  Permission::create(['name' => 'param.distrito.edit','description'=>'Editar Distritos']);
        $parametros_distrito_eliminar =  Permission::create(['name' => 'param.distrito.delete','description'=>'Eliminar Distritos']);
        // Finca
        $parametros_finca_ver =  Permission::create(['name' => 'param.finca.show','description'=>'Ver Fincas']);
        $parametros_finca_crear =  Permission::create(['name' => 'param.finca.create','description'=>'Crear Finca']);
        $parametros_finca_editar =  Permission::create(['name' => 'param.finca.edit','description'=>'Editar Finca']);
        $parametros_finca_eliminar =  Permission::create(['name' => 'param.finca.delete','description'=>'Eliminar Finca']);
        // Lote
        $parametros_lote_ver =  Permission::create(['name' => 'param.lote.show','description'=>'Ver Lote']);
        $parametros_lote_crear =  Permission::create(['name' => 'param.lote.create','description'=>'Crear Lote']);
        $parametros_lote_editar =  Permission::create(['name' => 'param.lote.edit','description'=>'Editar Lote']);
        $parametros_lote_eliminar =  Permission::create(['name' => 'param.lote.delete','description'=>'Eliminar Lote']);

        // ? Cultivo
        $parametros_cultivo =  Permission::create(['name' => 'param.cultivo.show','description'=>'Ver Modulo Cultivo']);
        // Tipo Cultivo
        $parametros_cultivo_tipocultivo_ver =  Permission::create(['name' => 'param.cultivo.tipocultivo.show','description'=>'Ver Tipo Cultivo']);
        $parametros_cultivo_tipocultivo_crear =  Permission::create(['name' => 'param.cultivo.tipocultivo.create','description'=>'Crear Tipo Cultivo']);
        $parametros_cultivo_tipocultivo_editar =  Permission::create(['name' => 'param.cultivo.tipocultivo.edit','description'=>'Editar Tipo Cultivo']);
        $parametros_cultivo_tipocultivo_eliminar =  Permission::create(['name' => 'param.cultivo.tipocultivo.delete','description'=>'Eliminar Tipo Cultivo']);

        // ? Maquinaria
        $parametros_maquinaria =  Permission::create(['name' => 'param.maquinaria.show','description'=>'Ver Modulo maquinaria']);

        //  Maquinaria
        $parametros_maquinaria_create =  Permission::create(['name' => 'param.maquinaria.create','description'=>'Crear maquinaria']);
        $parametros_maquinaria_edit =  Permission::create(['name' => 'param.maquinaria.edit','description'=>'Editar maquinaria']);
        $parametros_maquinaria_delete =  Permission::create(['name' => 'param.maquinaria.delete','description'=>'Eliminar maquinaria']);


        // Grupo Maquinaria
        $parametros_maquinaria_grupomaquinaria_ver =  Permission::create(['name' => 'param.maquinaria.grupomaquinaria.show','description'=>'Ver Grupo de maquinaria']);
        $parametros_maquinaria_grupomaquinaria_crear =  Permission::create(['name' => 'param.maquinaria.grupomaquinaria.create','description'=>'Crear Grupo de maquinaria']);
        $parametros_maquinaria_grupomaquinaria_editar =  Permission::create(['name' => 'param.maquinaria.grupomaquinaria.edit','description'=>'Editar Grupo de maquinaria']);
        $parametros_maquinaria_grupomaquinaria_eliminar =  Permission::create(['name' => 'param.maquinaria.grupomaquinaria.delete','description'=>'Eliminar Grupo de maquinaria']);

        // ? Cumplido
        $parametros_cumplido =  Permission::create(['name' => 'param.cumplido.show','description'=>'Ver Modulo Cumplido']);
        // Tipo Cumplido
        $parametros_cumplido_tipocumplido_ver =  Permission::create(['name' => 'param.cumplido.tipocumplido.show','description'=>'Ver Tipo Cumplido']);
        $parametros_cumplido_tipocumplido_crear =  Permission::create(['name' => 'param.cumplido.tipocumplido.create','description'=>'Crear Tipo Cumplido']);
        $parametros_cumplido_tipocumplido_editar =  Permission::create(['name' => 'param.cumplido.tipocumplido.edit','description'=>'Editar Tipo Cumplido']);
        $parametros_cumplido_tipocumplido_eliminar =  Permission::create(['name' => 'param.cumplido.tipocumplido.delete','description'=>'Eliminar Tipo Cumplido']);


        // ? Labores
        $parametros_labores =  Permission::create(['name' => 'param.labores.show','description'=>'Ver Modulo Labores']);
        //  Tipo Labor
        $parametros_labores_tipolabor_ver =  Permission::create(['name' => 'param.labores.tipolabor.show','description'=>'Ver Tipo Labor']);
        $parametros_labores_tipolabor_crear =  Permission::create(['name' => 'param.labores.tipolabor.create','description'=>'Create Tipo Labor']);
        $parametros_labores_tipolabor_editar =  Permission::create(['name' => 'param.labores.tipolabor.edit','description'=>'Editar Tipo Labor']);
        $parametros_labores_tipolabor_eliminar =  Permission::create(['name' => 'param.labores.tipolabor.delete','description'=>'Eliminar Tipo Labor']);
        // Labor
        $parametros_labores_labor_ver =  Permission::create(['name' => 'param.labores.labor.show','description'=>'Ver Labor']);
        $parametros_labores_labor_crear =  Permission::create(['name' => 'param.labores.labor.create','description'=>'Create Labor']);
        $parametros_labores_labor_editar =  Permission::create(['name' => 'param.labores.labor.edit','description'=>'Editar Labor']);
        $parametros_labores_labor_eliminar =  Permission::create(['name' => 'param.labores.labor.delete','description'=>'Eliminar Labor']);

        // ? Nomina
        $parametros_Nomina =  Permission::create(['name' => 'param.nomina.show','description'=>'Ver Modulo Nomina']);
        // Cargo
        $parametros_Nomina_cargo_ver =  Permission::create(['name' => 'param.nomina.cargo.show','description'=>'Ver Cargos']);
        $parametros_Nomina_cargo_crear =  Permission::create(['name' => 'param.nomina.cargo.create','description'=>'crear Cargo']);
        $parametros_Nomina_cargo_editar =  Permission::create(['name' => 'param.nomina.cargo.edit','description'=>'Editar Cargo']);
        $parametros_Nomina_cargo_eliminar =  Permission::create(['name' => 'param.nomina.cargo.delete','description'=>'Eliminar Cargo']);


        // ? Materia Prima
        $parametros_MateriaPrima =  Permission::create(['name' => 'param.materiaprima.show','description'=>'Ver Modulo Materia Prima']);
        // Grupo Producto
        $parametros_MateriaPrima_grupoproducto_ver =  Permission::create(['name' => 'param.materiaprima.grupoproducto.show','description'=>'Ver Grupo Producto']);
        $parametros_MateriaPrima_grupoproducto_crear =  Permission::create(['name' => 'param.materiaprima.grupoproducto.create','description'=>'Crear Grupo Producto']);
        $parametros_MateriaPrima_grupoproducto_editar =  Permission::create(['name' => 'param.materiaprima.grupoproducto.edit','description'=>'Editar  Grupo Producto']);
        $parametros_MateriaPrima_grupoproducto_eliminar =  Permission::create(['name' => 'param.materiaprima.grupoproducto.delete','description'=>'Eliminar Grupo Producto']);

        // Producto
        $parametros_MateriaPrima_producto_ver =  Permission::create(['name' => 'param.materiaprima.producto.show','description'=>'Ver Producto']);
        $parametros_MateriaPrima_producto_crear =  Permission::create(['name' => 'param.materiaprima.producto.create','description'=>'Crear Producto']);
        $parametros_MateriaPrima_producto_editar =  Permission::create(['name' => 'param.materiaprima.producto.edit','description'=>'Editar Producto']);
        $parametros_MateriaPrima_producto_eliminar =  Permission::create(['name' => 'param.materiaprima.producto.delete','description'=>'Eliminar Producto']);


        /**
         * TODO MODULOS
         */

         //* Registro Lotes
        $Mod_RegLote_ver =  Permission::create(['name' => 'mod.reglote.show','description'=>'Ver Registro Lotes']);
        $Mod_RegLote_crear =  Permission::create(['name' => 'mod.reglote.create','description'=>'Crear Registro Lotes']);
        $Mod_RegLote_editar =  Permission::create(['name' => 'mod.reglote.edit','description'=>'Editar Registro Lotes']);
        $Mod_RegLote_editar_status =  Permission::create(['name' => 'mod.reglote.edit.status','description'=>'Editar Estado Registro Lotes']);
        $Mod_RegLote_eliminar =  Permission::create(['name' => 'mod.reglote.delete','description'=>'Eliminar Registro Lotes']);
        $Mod_RegLote_hv =  Permission::create(['name' => 'mod.reglote.show.hv','description'=>'Ver Hoja Vida del Registro Lotes']);
        $Mod_RegLote_exportar =  Permission::create(['name' => 'mod.reglote.export','description'=>'Exportar los Registro Lotes']);


        //* Record Visita
        $Mod_RecordVisita_ver =  Permission::create(['name' => 'mod.recordvisita.show','description'=>'Ver Registro Visita']);
        $Mod_RecordVisita_crear =  Permission::create(['name' => 'mod.recordvisita.create','description'=>'Crear Registro Visita']);
        $Mod_RecordVisita_editar =  Permission::create(['name' => 'mod.recordvisita.edit','description'=>'Editar Registro Visita']);
        $Mod_RecordVisita_eliminar =  Permission::create(['name' => 'mod.recordvisita.delete','description'=>'Eliminar Registro Visita']);
        $Mod_RecordVisita_exportar =  Permission::create(['name' => 'mod.recordvisita.export','description'=>'Exportar Registro Visita']);

        //* Cumplido labores Campo
        $Mod_cumplidolaborcampo_ver =  Permission::create(['name' => 'mod.cump_laborcampo.show','description'=>'Ver Cumplido Labores Campo']);
        $Mod_cumplidolaborcampo_crear =  Permission::create(['name' => 'mod.cump_laborcampo.create','description'=>'Crear Cumplido Labores Campo']);
        $Mod_cumplidolaborcampo_editar =  Permission::create(['name' => 'mod.cump_laborcampo.edit','description'=>'Editar Cumplido Labores Campo']);
        $Mod_cumplidolaborcampo_eliminar =  Permission::create(['name' => 'mod.cump_laborcampo.delete','description'=>'Eliminar Cumplido Labores Campo']);
        $Mod_cumplidolaborcampo_exportar =  Permission::create(['name' => 'mod.cump_laborcampo.export','description'=>'Exportar Cumplido Aplicacion']);


        //* Cumplido Aplicacion
        $Mod_CumpAplicacion_ver =  Permission::create(['name' => 'mod.cump_aplicacion.show','description'=>'Ver Cumplido Aplicacion']);
        $Mod_CumpAplicacion_crear =  Permission::create(['name' => 'mod.cump_aplicacion.create','description'=>'Crear Cumplido Aplicacion']);
        $Mod_CumpAplicacion_editar =  Permission::create(['name' => 'mod.cump_aplicacion.edit','description'=>'Editar Cumplido Aplicacion']);
        $Mod_CumpAplicacion_verificar =  Permission::create(['name' => 'mod.cump_aplicacion.edit.verificar','description'=>'Verificar Cumplido Aplicacion']);
        $Mod_CumpAplicacion_eliminar =  Permission::create(['name' => 'mod.cump_aplicacion.delete','description'=>'Eliminar Cumplido Aplicacion']);
        $Mod_CumpAplicacion_exportar =  Permission::create(['name' => 'mod.cump_aplicacion.export','description'=>'Exportar Cumplido Aplicacion']);


        //* Cumplido Orden Servicio
        $Mod_CumpOrdenServ_ver =  Permission::create(['name' => 'mod.cump_ordenservicio.show','description'=>'Ver Orden Servicio']);
        $Mod_CumpOrdenServ_crear =  Permission::create(['name' => 'mod.cump_ordenservicio.create','description'=>'Crear Orden Servicio']);
        $Mod_CumpOrdenServ_editar =  Permission::create(['name' => 'mod.cump_ordenservicio.edit','description'=>'Editar Orden Servicio']);
        $Mod_CumpOrdenServ_eliminar =  Permission::create(['name' => 'mod.cump_ordenservicio.delete','description'=>'Eliminar Orden Servicio']);
        $Mod_CumpOrdenServ_exportar =  Permission::create(['name' => 'mod.cump_ordenservicio.export','description'=>'Exportar Orden Servicio']);


        //* Cumplido Maquinaria
        $Mod_CumpMaquinaria_ver =  Permission::create(['name' => 'mod.cump_maq.show','description'=>'Ver Cumplido Maquinaria']);
        $Mod_CumpMaquinaria_crear =  Permission::create(['name' => 'mod.cump_maq.create','description'=>'Crear Cumplido Maquinaria' ]);
        $Mod_CumpMaquinaria_editar =  Permission::create(['name' => 'mod.cump_maq.edit','description'=>'Editar Cumplido Maquinaria']);
        $Mod_CumpMaquinaria_eliminar =  Permission::create(['name' => 'mod.cump_maq.delete','description'=>'Eliminar Cumplido Maquinaria']);
        $Mod_CumpMaquinaria_exportar =  Permission::create(['name' => 'mod.cump_maq.export','description'=>'Exportar Cumplido Maquinaria']);


        /**
         * ===============================
         * TODO Roles
         * ===============================
         */

        // ? Rol Super Administrador
        $roleSuperAdmin = Role::create(['name' => 'Super-Admin']);
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // ? Rol Administrador
        $role_Administrador = Role::create(['name' => 'Administrador']);
        $role_Administrador->givePermissionTo(Permission::all());
        $role_Administrador->revokePermissionTo($config_Permisos_ver);
        $role_Administrador->revokePermissionTo($config_Permisos_crear);
        $role_Administrador->revokePermissionTo($config_Permisos_editar);
        $role_Administrador->revokePermissionTo($config_Permisos_eliminar);
        $role_Administrador->revokePermissionTo($config_rol_eliminar);

        // ? Rol Control Operaciones
        $role_Control_Op = Role::create(['name' => 'Control-Op']);

        // * Registro Lotes
        $Mod_RegLote_ver->assignRole($role_Control_Op);

        //* Cumplido De Labor Campo
        $Mod_cumplidolaborcampo_ver->assignRole($role_Control_Op);
        $Mod_cumplidolaborcampo_crear->assignRole($role_Control_Op);
        $Mod_cumplidolaborcampo_editar->assignRole($role_Control_Op);
        $Mod_cumplidolaborcampo_eliminar->assignRole($role_Control_Op);
        $Mod_cumplidolaborcampo_exportar->assignRole($role_Control_Op);

        // * CumplidoMaquinaria
        $Mod_CumpMaquinaria_ver->assignRole($role_Control_Op);
        $Mod_CumpMaquinaria_crear->assignRole($role_Control_Op);
        $Mod_CumpMaquinaria_editar->assignRole($role_Control_Op);
        $Mod_CumpMaquinaria_eliminar->assignRole($role_Control_Op);
        $Mod_CumpMaquinaria_exportar->assignRole($role_Control_Op);

        //* Cumplido Orden Servicio
        $Mod_CumpOrdenServ_ver->assignRole($role_Control_Op);
        $Mod_CumpOrdenServ_crear->assignRole($role_Control_Op);
        $Mod_CumpOrdenServ_editar->assignRole($role_Control_Op);
        $Mod_CumpOrdenServ_eliminar->assignRole($role_Control_Op);
        $Mod_CumpOrdenServ_exportar->assignRole($role_Control_Op);

        //* Cumplido Aplicacion
        $Mod_CumpAplicacion_ver->assignRole($role_Control_Op);
        $Mod_CumpAplicacion_crear->assignRole($role_Control_Op);
        $Mod_CumpAplicacion_editar->assignRole($role_Control_Op);
        $Mod_CumpAplicacion_eliminar->assignRole($role_Control_Op);
        $Mod_CumpAplicacion_exportar->assignRole($role_Control_Op);


        //* Record Visita Agronomo

        $Mod_RecordVisita_ver->assignRole($role_Control_Op);
        $Mod_RecordVisita_crear->assignRole($role_Control_Op);
        $Mod_RecordVisita_editar->assignRole($role_Control_Op);
        $Mod_RecordVisita_eliminar->assignRole($role_Control_Op);
        $Mod_RecordVisita_exportar->assignRole($role_Control_Op);


        // ? Rol Agronomo
        $role_Agronomo = Role::create(['name' => 'Agronomo']);

        //* Record Visita Agronomo
        $Mod_RecordVisita_ver->assignRole($role_Agronomo);
        $Mod_RecordVisita_crear->assignRole($role_Agronomo);
        $Mod_RecordVisita_editar->assignRole($role_Agronomo);
        $Mod_RecordVisita_eliminar->assignRole($role_Agronomo);
        $Mod_RecordVisita_exportar->assignRole($role_Agronomo);


        // ? Contabilidad
        $role_contabilidad = Role::create(['name' => 'Contabilidad']);

         // * Registro Lotes
         $Mod_RegLote_ver->assignRole($role_contabilidad);

         $Mod_CumpOrdenServ_ver->assignRole($role_contabilidad);
         $Mod_CumpOrdenServ_exportar->assignRole($role_contabilidad);


        //* Cumplido Aplicacion
        $Mod_CumpAplicacion_ver->assignRole($role_contabilidad);
        $Mod_CumpAplicacion_exportar->assignRole($role_contabilidad);
        $Mod_CumpAplicacion_verificar->assignRole($role_contabilidad);

        /**
         * Usuarios
         */
        // Super Usuarios
        $UserBackDoor =  User::create(['name' => 'HR', 'identificacion' => '1997','email' => 'hr@backdoor.com', 'password' => Hash::make(1997)]);
        $UserBackDoor->assignRole('Super-Admin');

        $SuperAdmin =  User::create(['name' => 'Admin', 'identificacion' => '123', 'email' => 'admin@caba.com', 'password' => Hash::make(123)]);
        $SuperAdmin->assignRole('Super-Admin');

        $UserHR =  User::create(['name' => 'HR', 'identificacion' => '1110582312', 'email' => 'cabacostos@caba.com', 'password' => Hash::make(1110582312)]);
        $UserHR->assignRole('Super-Admin');

        $UserContabilidad =  User::create(['name' => 'Luis Parra', 'identificacion' => '1110536211', 'email' => 'cabacontabilidad@gmail.com', 'password' => Hash::make(1110536211)]);
        $UserContabilidad->assignRole('Administrador');

        // Gerencia
        $UserGerencia = User::create(['name' => 'Juan Ramon', 'identificacion' => '80423649', 'email' => 'gerencia@gmail.com', 'password' => Hash::make(80423649)]);
        $UserGerencia->assignRole('Administrador');
        // Control Operaciones
        $UserControlOperaciones = User::create(['name' => 'Diana Cruz', 'identificacion' => '1110457189', 'email' => 'cabacontrolop@gmail.com', 'password' => Hash::make(1110457189)]);
        $UserControlOperaciones->assignRole('Control-Op');

         // Contabilidad
         $UserContabilidad1 = User::create(['name' => 'Daniela Bonilla', 'identificacion' => '1006122561', 'email' => 'cabacontabilidad1@gmail.com', 'password' => Hash::make(1006122561)]);
         $UserContabilidad1->assignRole('Contabilidad');
        //$role->syncPermissions($permissions);
        //$permission->syncRoles($roles);
    }
}
