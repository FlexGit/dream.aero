<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/fly-737/styles.css?v=10') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/fly-737/default.css?v=10') }}" rel="stylesheet" type="text/css" />
<style type="text/css">
	.modal-header {
		border-bottom: 0;
	}
	.modal-content {
		border: 3px solid #FF8200;
		border-radius: 0;
	}
	.modal-dialog {
		-webkit-transform: translate(0,0);
		transform: translate(0,0);
		transition: transform .3s ease-out,
		-webkit-transform .3s ease-out;
		width: 450px;
	}
	.gl-default, .gl-change-select {
		width: inherit;
		padding-top: 0;
	}
	.dropdown-menu {
		top: auto;
		left: auto;
	}
	.grecaptcha-badge {
		visibility: hidden !important;
	}
	.nice-select {
		height: 28px;
		color: #828285 !important;;
		padding-left: 8px;
		padding-right: 20px;
		/*overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;*/
		line-height: 28px;
		border: 0 !important;
		border-radius: 0 !important;
		border-bottom: 2px solid #828285 !important;
	}
	.popup-description {
		margin-top: 0;
	}
	.cont {
		margin-bottom: 7px;
		padding-top: 3px;
	}
	.popup .nice-select {
		padding-left: 10px;
	}
	.popup .popup-small-button {
		margin: 1px 0 0 5px;
		padding: 0;
		width: 90px;
		height: 24px;
		font-size: 12px;
	}
	@media screen and (max-width: 767px) {
		.popup {
			padding-left: 15px !important;
			padding-right: 15px !important;;
		}
	}
	.text-error {
		color: red;
	}
	.text-success {
		color: green;
	}
	.text-small {
		font-size: 12px;
	}
	.button-pipaluk-grey:after {
		background-color: #bbb !important;
	}
	.button-pipaluk-unactive:after {
		background-color: #bbb !important;
	}
	.button-pipaluk-unactive:before {
		border-color: #bbb !important;
	}
	.close-btn {
		margin: 3px 0 0 2px;
		cursor: pointer;
	}
	.pl-10 {
		padding-left: 10px;
	}
	.pr-10 {
		padding-right: 10px;
	}
	.pt-3 {
		padding-top: 3px;
	}

	.overlay {
		position: fixed;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		background: rgba(0, 0, 0, 0.7);
		transition: opacity 500ms;
		visibility: hidden;
		opacity: 0;
		z-index: 999;
	}
	.overlay:target {
		visibility: visible;
		opacity: 1;
	}

	.popup-promo {
		margin: 250px auto;
		padding: 20px;
		background: #fff;
		border-radius: 5px;
		width: 30%;
		position: relative;
		transition: all 5s ease-in-out;
		min-height: 170px;
	}

	.popup-promo h2 {
		margin-top: 0;
		color: #333;
		/*font-family: Tahoma, Arial, sans-serif;*/
	}
	.popup-promo .close {
		position: absolute;
		top: 10px;
		right: 20px;
		transition: all 200ms;
		font-size: 30px;
		font-weight: bold;
		text-decoration: none;
		color: #333;
	}
	.popup-promo .close:hover {
		color: #000;
	}
	.popup-promo .content {
		max-height: 30%;
		overflow: auto;
		text-align: center;
		margin-top: 20px;
	}

	@media screen and (max-width: 700px){
		.popup-promo {
			width: 70%;
		}
	}
</style>
