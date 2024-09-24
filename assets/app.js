// assets/app.js
import {registerReactControllerComponents} from '@symfony/ux-react';
import './bootstrap';

console.log(registerReactControllerComponents);
registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));
