<?php
/**
 * @file SimplexTest.php
 * @description Suite completa de pruebas unitarias automatizadas para TODOS los módulos 
 * componentes del ERP SIMPLEX (Seguridad, Inventarios, Clientes, Categorías, Proveedores y Finanzas).
 */

use PHPUnit\Framework\TestCase;

// =========================================================================
// 1. SIMULACIÓN DE CLASES Y REGLAS DE NEGOCIO (BACKEND - MVC)
// =========================================================================

class AuthController {
    public static function validar(string $usuario, string $contrasena) {
        if ($usuario === 'ANDRES1' && $contrasena === 'Cali2024') return true;
        if ($usuario === 'MAGVD12' && $contrasena === '12345678') return true;
        return false;
    }
}

class Producto {
    private $stock;
    public function setStock(int$valor) {
        if ($valor < 0) {
            throw new InvalidArgumentException("El stock no puede ser negativo");
        }
        $this->stock = $valor;
    }
}

class Cliente {
    private $nombre;
    private $email;
    public function setNombre(string $nombre) {
        if (empty(trim($nombre))) {
            throw new InvalidArgumentException("Nombre obligatorio");
        }
        $this->nombre = $nombre;
    }
    public function setEmail(string $email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email inválido");
        }
        $this->email = $email;
    }
}

class Categoria {
    private $nombre;
    public function setNombre(string $nombre) {
        if (strlen($nombre) < 3) {
            throw new InvalidArgumentException("El nombre de la categoría debe tener al menos 3 caracteres");
        }
        $this->nombre = $nombre;
    }
}

class Proveedor {
    private $telefono;
    public function setTelefono(string $telefono) {
        if (!preg_match('/^[0-9]+$/', $telefono)) {
            throw new InvalidArgumentException("El teléfono del proveedor solo debe contener números");
        }
        $this->telefono = $telefono;
    }
}

class MovimientoContable {
    private $valor;
    public function setValor(float $valor) {
        if ($valor <= 0) {
            throw new InvalidArgumentException("El valor del movimiento contable debe ser mayor a cero");
        }
        $this->valor = $valor;
    }
}

// =========================================================================
// 2. EJECUCIÓN DE PRUEBAS UNITARIAS DE CADA MÓDULO (PHPUNIT TEST CASES)
// =========================================================================

class SimplexTest extends TestCase {
    
    // ----------- MÓDULO 1: SEGURIDAD Y ACCESO -----------
    public function testModuloSeguridadLoginCorrecto() {
        $this->assertTrue(AuthController::validar('ANDRES1', 'Cali2024'));
    }

    public function testModuloSeguridadLoginClaveErronea() {
        $this->assertFalse(AuthController::validar('MAGVD12', 'incorrecta'));
    }

    // ----------- MÓDULO 2: INVENTARIO (PRODUCTOS) -----------
    public function testModuloProductosNoAdmiteStockNegativo() {
        $producto = new Producto();
        $this->expectException(InvalidArgumentException::class);
        $producto->setStock(-5);
    }

    // ----------- MÓDULO 3: GESTIÓN DE CLIENTES -----------
    public function testModuloClientesValidaEmail() {
        $cliente = new Cliente();
        $this->expectException(InvalidArgumentException::class);
        $cliente->setEmail("correo_invalido.com");
    }

    // ----------- MÓDULO 4: CATEGORÍAS -----------
    public function testModuloCategoriasExigeNombreValido() {
        $categoria = new Categoria();
        $this->expectException(InvalidArgumentException::class);
        $categoria->setNombre("A"); // Muy corto, debe fallar
    }

    // ----------- MÓDULO 5: PROVEEDORES -----------
    public function testModuloProveedoresRechazaLetrasEnTelefono() {
        $proveedor = new Proveedor();
        $this->expectException(InvalidArgumentException::class);
        $proveedor->setTelefono("312-ABC-456"); // Contiene letras, debe fallar
    }

    // ----------- MÓDULO 6: MOVIMIENTOS CONTABLES (CAJA MENOR) -----------
    public function testModuloContabilidadRechazaValoresEnCero() {
        $movimiento = new MovimientoContable();
        $this->expectException(InvalidArgumentException::class);
        $movimiento->setValor(0); // Transacción inválida, debe fallar
    }
}

