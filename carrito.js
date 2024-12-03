function eliminarProducto(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
        fetch(`eliminar_producto.php?id_obra=${id}`)
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload(); // Recarga la página para mostrar los cambios
            });
    }
}
