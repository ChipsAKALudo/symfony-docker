import { registerReactControllerComponents } from '@symfony/ux-react';
import './bootstrap.js';
// assets/app.js
import './bootstrap';
import './styles/app.css';
import './styles/globals.css';

console.log(registerReactControllerComponents);
registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));
