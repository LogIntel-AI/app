import{a as e,d as t,h as n,i as r,m as i,n as a,r as o,t as s,u as c}from"./three.module-TpoRxv_-.js";var l=null;function u(u){let d=document.getElementById(u);if(!d||l)return;let f=new i,p=new c(60,window.innerWidth/window.innerHeight,.1,1e3),m=new s({alpha:!0,antialias:!0}),h=window.innerWidth<768?80:180,g={x:12,y:16,z:4},_=new Float32Array(h*3),v=[],y=new Float32Array(h);m.setSize(window.innerWidth,window.innerHeight),m.setPixelRatio(Math.min(window.devicePixelRatio,2)),m.domElement.setAttribute(`aria-hidden`,`true`),m.domElement.className=`absolute inset-0 h-full w-full`,d.innerHTML=``,d.appendChild(m.domElement);for(let e=0;e<h;e++){let t=e*3;_[t]=(Math.random()-.5)*g.x,_[t+1]=(Math.random()-.5)*g.y,_[t+2]=(Math.random()-.5)*g.z,v.push({y:-(Math.random()*.04+.02)}),y[e]=Math.random()*.8+.2}let b=new o;b.setAttribute(`position`,new a(_,3)),b.setAttribute(`size`,new a(y,1));let x=new n({uniforms:{color:{value:new e(8702998)},opacity:{value:.6}},vertexShader:`
            attribute float size;
            varying float vAlpha;
            void main() {
                vAlpha = 0.4 + (size * 0.3);
                vec4 mvPosition = modelViewMatrix * vec4(position, 1.0);
                gl_PointSize = size * (20.0 / -mvPosition.z);
                gl_Position = projectionMatrix * mvPosition;
            }
        `,fragmentShader:`
            uniform vec3 color;
            uniform float opacity;
            varying float vAlpha;
            void main() {
                // make it a vertical rectangle (like a line of code)
                vec2 uv = gl_PointCoord.xy - vec2(0.5);
                if (abs(uv.x) > 0.1 || abs(uv.y) > 0.4) discard;
                gl_FragColor = vec4(color, vAlpha * opacity);
            }
        `,transparent:!0,depthWrite:!1,blending:1}),S=new t(b,x);f.add(S),p.position.z=6;let C=new r,w=()=>{let e=document.documentElement.classList.contains(`dark`);x.uniforms.color.value.setHex(e?1096065:8702998),x.uniforms.opacity.value=e?.8:.6,x.blending=e?2:1,x.needsUpdate=!0};w(),new MutationObserver(w).observe(document.documentElement,{attributes:!0,attributeFilter:[`class`]});function T(){for(let e=0;e<h;e++){let t=e*3,n=v[e];_[t+1]+=n.y,_[t+1]<-g.y/2&&(_[t+1]=g.y/2,_[t]=(Math.random()-.5)*g.x)}b.attributes.position.needsUpdate=!0}function E(){l=requestAnimationFrame(E),T();let e=C.getElapsedTime();f.rotation.y=e*.15,m.render(f,p)}E();let D;window.addEventListener(`resize`,()=>{clearTimeout(D),D=setTimeout(()=>{p.aspect=window.innerWidth/window.innerHeight,p.updateProjectionMatrix(),m.setSize(window.innerWidth,window.innerHeight)},120)})}document.addEventListener(`DOMContentLoaded`,()=>{document.getElementById(`landing-three-container`)&&u(`landing-three-container`)});