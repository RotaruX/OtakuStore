/**
 * fecha_pedidos.js
 * Utiliza el objeto Date de JavaScript para mostrar
 * hace cuánto tiempo se realizó cada pedido (tiempo relativo).
 */
document.addEventListener("DOMContentLoaded", function () {

  // Seleccionar todos los elementos con la fecha del pedido
  const elementosFecha = document.querySelectorAll(".pedido-fecha[data-fecha]");

  elementosFecha.forEach(function (el) {
    const fechaISO = el.getAttribute("data-fecha");

    // Crear objeto Date a partir de la fecha del servidor
    const fechaPedido = new Date(fechaISO);
    const ahora       = new Date();

    // Calcular la diferencia en milisegundos
    const diffMs      = ahora.getTime() - fechaPedido.getTime();
    const diffMinutos = Math.floor(diffMs / (1000 * 60));
    const diffHoras   = Math.floor(diffMs / (1000 * 60 * 60));
    const diffDias    = Math.floor(diffMs / (1000 * 60 * 60 * 24));
    const diffSemanas = Math.floor(diffDias / 7);
    const diffMeses   = Math.floor(diffDias / 30);

    // Generar texto relativo
    let textoRelativo = "";
    if (diffMinutos < 1) {
      textoRelativo = "Justo ahora";
    } else if (diffMinutos < 60) {
      textoRelativo = "Hace " + diffMinutos + (diffMinutos === 1 ? " minuto" : " minutos");
    } else if (diffHoras < 24) {
      textoRelativo = "Hace " + diffHoras + (diffHoras === 1 ? " hora" : " horas");
    } else if (diffDias < 7) {
      textoRelativo = "Hace " + diffDias + (diffDias === 1 ? " día" : " días");
    } else if (diffSemanas < 5) {
      textoRelativo = "Hace " + diffSemanas + (diffSemanas === 1 ? " semana" : " semanas");
    } else {
      textoRelativo = "Hace " + diffMeses + (diffMeses === 1 ? " mes" : " meses");
    }

    // Formatear hora exacta con Date
    const horaFormateada = fechaPedido.toLocaleString("es-ES", {
      day:    "2-digit",
      month:  "2-digit",
      year:   "numeric",
      hour:   "2-digit",
      minute: "2-digit",
    });

    // Crear el span de tiempo relativo y añadirlo al DOM
    const spanRelativo = document.createElement("span");
    spanRelativo.className = "pedido-fecha-relativa";
    spanRelativo.textContent = " · " + textoRelativo;
    spanRelativo.title = "Fecha exacta: " + horaFormateada;
    el.appendChild(spanRelativo);
  });
});
