const canvas = document.getElementById("characterCanvas");
const ctx = canvas.getContext("2d");

  const x1 = document.querySelector("#x1");
    const x2 = document.querySelector("#x2");
    const y = document.querySelector("#y");
    const color = document.querySelector("#color");

let xPosition = canvas.width / 2;
let yPosition = canvas.height;


function draw() {
  
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawCube(xPosition, yPosition, +x1.value, +x2.value, +y.value, color.value);
    requestAnimationFrame(draw);
}

draw();

function shadeColor(color, percent) {
    const num = parseInt(color.substr(1), 16);
    const amt = Math.round(2.55 * percent);
    const R = Math.min(255, Math.max(0, (num >> 16) + amt));
    const G = Math.min(255, Math.max(0, ((num >> 8) & 0xff) + amt));
    const B = Math.min(255, Math.max(0, (num & 0xff) + amt));
    return `#${((R << 16) | (G << 8) | B).toString(16).padStart(6, "0")}`;
}

function drawCube(x, y, wx, wy, h, color) {
    const vertices = [
        [0, 0],
        [-wx, -wx * 0.5],
        [-wx, -h - wx * 0.5],
        [0, -h],
        [0, 0],
        [wy, -wy * 0.5],
        [wy, -h - wy * 0.5],
        [0, -h],
        [0, -h],
        [-wx, -h - wx * 0.5],
        [-wx + wy, -h - (wx * 0.5 + wy * 0.5)],
        [wy, -h - wy * 0.5],
    ];

    const fillColors = [0, 10, 20];
    const strokeColors = [0, 30, 60];

    for (let i = 0; i < 3; i++) {
        const startIndex = i * 4;
        ctx.beginPath();
        ctx.moveTo(x + vertices[startIndex][0], y + vertices[startIndex][1]);

        for (let j = 1; j < 4; j++) {
            ctx.lineTo(
                x + vertices[startIndex + j][0],
                y + vertices[startIndex + j][1]
            );
        }

        ctx.closePath();
        ctx.fillStyle = shadeColor(color, fillColors[i]);
        ctx.strokeStyle = shadeColor(color, strokeColors[i]);
        ctx.lineWidth = 1;
        ctx.stroke();
        ctx.fill();
    }
}

function moveLeft() {
    xPosition -= 10;
}
function moveRight() {
    xPosition += 10;
}
function moveUp() {
    yPosition -= 10;
}
function moveDown() {
    yPosition += 10;
}