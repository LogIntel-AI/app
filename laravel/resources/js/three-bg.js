import * as THREE from 'three';

let activeScene = null;

export function initThreeJS(containerId) {
    const container = document.getElementById(containerId);
    if (!container || activeScene) return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(58, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    const particlesCount = window.innerWidth < 768 ? 90 : 150;
    const bounds = { x: 8, y: 4.8, z: 3.4 };
    const positions = new Float32Array(particlesCount * 3);
    const velocities = [];

    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.domElement.setAttribute('aria-hidden', 'true');
    renderer.domElement.className = 'absolute inset-0 h-full w-full';
    container.innerHTML = '';
    container.appendChild(renderer.domElement);

    for (let index = 0; index < particlesCount; index++) {
        const positionIndex = index * 3;
        positions[positionIndex] = (Math.random() - 0.5) * bounds.x;
        positions[positionIndex + 1] = (Math.random() - 0.5) * bounds.y;
        positions[positionIndex + 2] = (Math.random() - 0.5) * bounds.z;
        velocities.push({
            x: (Math.random() - 0.5) * 0.004,
            y: (Math.random() - 0.5) * 0.003,
            z: (Math.random() - 0.5) * 0.004,
        });
    }

    const particleGeometry = new THREE.BufferGeometry();
    particleGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));

    const particleMaterial = new THREE.PointsMaterial({
        size: 0.035,
        transparent: true,
        opacity: 0.76,
        depthWrite: false,
        blending: THREE.AdditiveBlending,
    });

    const particles = new THREE.Points(particleGeometry, particleMaterial);
    scene.add(particles);

    const lineGeometry = new THREE.BufferGeometry();
    const linePositions = new Float32Array(particlesCount * particlesCount * 3);
    const lineMaterial = new THREE.LineBasicMaterial({
        transparent: true,
        opacity: 0.18,
        depthWrite: false,
        blending: THREE.AdditiveBlending,
    });
    const lines = new THREE.LineSegments(lineGeometry, lineMaterial);
    scene.add(lines);

    const scanGeometry = new THREE.RingGeometry(1.2, 1.22, 96);
    const scanMaterial = new THREE.MeshBasicMaterial({
        transparent: true,
        opacity: 0.14,
        side: THREE.DoubleSide,
    });
    const scanRing = new THREE.Mesh(scanGeometry, scanMaterial);
    scanRing.position.set(2.6, 0.2, -0.7);
    scanRing.rotation.x = Math.PI / 2.2;
    scene.add(scanRing);

    camera.position.z = 5.2;

    let pointerX = 0;
    let pointerY = 0;

    const applyTheme = () => {
        const isDark = document.documentElement.classList.contains('dark');
        particleMaterial.color.setHex(isDark ? 0x68e8ff : 0x087f8f);
        lineMaterial.color.setHex(isDark ? 0x6ee7b7 : 0x0f766e);
        scanMaterial.color.setHex(isDark ? 0x22d3ee : 0x0f766e);
        particleMaterial.opacity = isDark ? 0.82 : 0.42;
        lineMaterial.opacity = isDark ? 0.2 : 0.12;
        scanMaterial.opacity = isDark ? 0.16 : 0.08;
    };

    applyTheme();

    const observer = new MutationObserver(applyTheme);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    document.addEventListener('mousemove', (event) => {
        pointerX = (event.clientX / window.innerWidth - 0.5) * 0.5;
        pointerY = (event.clientY / window.innerHeight - 0.5) * 0.35;
    });

    function updateParticles() {
        for (let index = 0; index < particlesCount; index++) {
            const positionIndex = index * 3;
            const velocity = velocities[index];

            positions[positionIndex] += velocity.x;
            positions[positionIndex + 1] += velocity.y;
            positions[positionIndex + 2] += velocity.z;

            if (Math.abs(positions[positionIndex]) > bounds.x / 2) velocity.x *= -1;
            if (Math.abs(positions[positionIndex + 1]) > bounds.y / 2) velocity.y *= -1;
            if (Math.abs(positions[positionIndex + 2]) > bounds.z / 2) velocity.z *= -1;
        }

        particleGeometry.attributes.position.needsUpdate = true;
    }

    function updateConnections() {
        let vertexPointer = 0;
        const maxDistance = window.innerWidth < 768 ? 0.95 : 0.78;

        for (let i = 0; i < particlesCount; i++) {
            for (let j = i + 1; j < particlesCount; j++) {
                const first = i * 3;
                const second = j * 3;
                const dx = positions[first] - positions[second];
                const dy = positions[first + 1] - positions[second + 1];
                const dz = positions[first + 2] - positions[second + 2];
                const distance = Math.sqrt(dx * dx + dy * dy + dz * dz);

                if (distance < maxDistance) {
                    linePositions[vertexPointer++] = positions[first];
                    linePositions[vertexPointer++] = positions[first + 1];
                    linePositions[vertexPointer++] = positions[first + 2];
                    linePositions[vertexPointer++] = positions[second];
                    linePositions[vertexPointer++] = positions[second + 1];
                    linePositions[vertexPointer++] = positions[second + 2];
                }
            }
        }

        lineGeometry.setAttribute('position', new THREE.BufferAttribute(linePositions.slice(0, vertexPointer), 3));
    }

    const clock = new THREE.Clock();

    function animate() {
        activeScene = requestAnimationFrame(animate);
        const elapsed = clock.getElapsedTime();

        updateParticles();
        updateConnections();

        particles.rotation.y = elapsed * 0.025 + pointerX;
        particles.rotation.x = pointerY;
        lines.rotation.copy(particles.rotation);
        scanRing.rotation.z = elapsed * 0.55;
        scanRing.scale.setScalar(1 + Math.sin(elapsed * 1.4) * 0.08);

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
