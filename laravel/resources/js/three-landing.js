import * as THREE from 'three';

let activeScene = null;

function initLandingThreeJS(containerId) {
    const container = document.getElementById(containerId);
    if (!container || activeScene) return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    
    // Create falling lines (logs)
    const logsCount = window.innerWidth < 768 ? 80 : 180;
    const bounds = { x: 12, y: 16, z: 4 };
    const positions = new Float32Array(logsCount * 3);
    const velocities = [];
    const sizes = new Float32Array(logsCount);

    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.domElement.setAttribute('aria-hidden', 'true');
    renderer.domElement.className = 'absolute inset-0 h-full w-full';
    container.innerHTML = '';
    container.appendChild(renderer.domElement);

    for (let index = 0; index < logsCount; index++) {
        const positionIndex = index * 3;
        positions[positionIndex] = (Math.random() - 0.5) * bounds.x;
        positions[positionIndex + 1] = (Math.random() - 0.5) * bounds.y;
        positions[positionIndex + 2] = (Math.random() - 0.5) * bounds.z;
        velocities.push({
            y: - (Math.random() * 0.04 + 0.02) // falling downwards
        });
        sizes[index] = Math.random() * 0.8 + 0.2;
    }

    const logsGeometry = new THREE.BufferGeometry();
    logsGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    logsGeometry.setAttribute('size', new THREE.BufferAttribute(sizes, 1));

    // Custom shader for drawing small vertical lines that look like text/logs
    const logsMaterial = new THREE.ShaderMaterial({
        uniforms: {
            color: { value: new THREE.Color(0x84cc16) }, // Lime color for light
            opacity: { value: 0.6 }
        },
        vertexShader: `
            attribute float size;
            varying float vAlpha;
            void main() {
                vAlpha = 0.4 + (size * 0.3);
                vec4 mvPosition = modelViewMatrix * vec4(position, 1.0);
                gl_PointSize = size * (20.0 / -mvPosition.z);
                gl_Position = projectionMatrix * mvPosition;
            }
        `,
        fragmentShader: `
            uniform vec3 color;
            uniform float opacity;
            varying float vAlpha;
            void main() {
                // make it a vertical rectangle (like a line of code)
                vec2 uv = gl_PointCoord.xy - vec2(0.5);
                if (abs(uv.x) > 0.1 || abs(uv.y) > 0.4) discard;
                gl_FragColor = vec4(color, vAlpha * opacity);
            }
        `,
        transparent: true,
        depthWrite: false,
        blending: THREE.NormalBlending,
    });

    const logs = new THREE.Points(logsGeometry, logsMaterial);
    scene.add(logs);

    camera.position.z = 6;

    const clock = new THREE.Clock();

    const applyTheme = () => {
        const isDark = document.documentElement.classList.contains('dark');
        // Light mode: Lime (0x84cc16). Dark mode: Glowing emerald/teal (0x10b981)
        logsMaterial.uniforms.color.value.setHex(isDark ? 0x10b981 : 0x84cc16);
        logsMaterial.uniforms.opacity.value = isDark ? 0.8 : 0.6;
        logsMaterial.blending = isDark ? THREE.AdditiveBlending : THREE.NormalBlending;
        logsMaterial.needsUpdate = true;
    };

    applyTheme();
    const observer = new MutationObserver(applyTheme);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    function updateParticles() {
        for (let index = 0; index < logsCount; index++) {
            const positionIndex = index * 3;
            const velocity = velocities[index];

            positions[positionIndex + 1] += velocity.y;

            // Reset to top if it falls below bound
            if (positions[positionIndex + 1] < -bounds.y / 2) {
                positions[positionIndex + 1] = bounds.y / 2;
                positions[positionIndex] = (Math.random() - 0.5) * bounds.x;
            }
        }
        logsGeometry.attributes.position.needsUpdate = true;
    }

    function animate() {
        activeScene = requestAnimationFrame(animate);
        updateParticles();
        
        // Scene revolves around its own axis
        const elapsed = clock.getElapsedTime();
        scene.rotation.y = elapsed * 0.15;

        renderer.render(scene, camera);
    }

    animate();

    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        }, 120);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('landing-three-container')) {
        initLandingThreeJS('landing-three-container');
    }
});
