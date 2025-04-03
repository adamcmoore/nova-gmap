<template>
	<DefaultField :field="field" :errors="errors">
		<template v-slot:field>
			<input ref="address" id="address" type="text" v-placeholder="placeholderText" v-model="address" />

			<div class="google-map" ref="map"></div>

			<div class="inputs">
				<input id="latitude" v-name="latitude_fieldname" type="hidden" v-model="latitude" />
				<input id="longitude" v-name="longitude_fieldname" type="hidden" v-model="longitude" />
			</div>
		</template>
	</DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
	mixins: [FormField, HandlesValidationErrors],

	props: ['resourceName', 'resourceId', 'field', 'placeholder'],

	data() {
		return {
			map: null,
			autocomplete: null,
			address: null,
			marker: null,
			latitude: null,
			longitude: null,
			placeholderText: this.placeholder
		}
	},

	computed: {
		latitude_fieldname: function() {
			return this.field.attribute+'[latitude]';
		},
		longitude_fieldname: function() {
			return this.field.attribute+'[longitude]';
		}
	},

	methods: {
		fill(formData) {
			formData.append(this.latitude_fieldname, this.value.latitude);
			formData.append(this.longitude_fieldname, this.value.longitude);
		},

		addMarker(lat, lng) {
			if (this.marker) {
				this.marker.setMap(null);
			}

			const coords = new google.maps.LatLng(lat, lng);

			this.marker = new google.maps.Marker({
				position: coords,
				map: this.map,
				draggable: true
			});

			this.marker.addListener('click', this.removeMarker);
			this.marker.addListener('dragend', this.updateCoords);
			this.value.latitude = lat;
			this.value.longitude = lng;
			this.map.setCenter(coords);
		},

		updateCoords(event) {
			this.value.latitude = event.latLng.lat();
			this.value.longitude = event.latLng.lng();
			this.map.setCenter(event.latLng);
		},

		removeMarker(event) {
			this.value.latitude = '';
			this.value.longitude = '';
			this.marker.setMap(null);
		}
	},

	mounted() {
		this.setInitialValue();

		const el = this.$refs['map'];
		const options = {
			zoom: this.field.zoom,
			center: new google.maps.LatLng(this.field.latitude, this.field.longitude)
		}

		this.field.fill = this.fill;

		this.map = new google.maps.Map(el, options);

        if (this.value.latitude && this.value.longitude) {
			this.addMarker(this.value.latitude, this.value.longitude);
		}

		google.maps.event.addListener(this.map, 'click', (event) => {
			this.latitude = event.latLng.lat();
			this.longitude = event.latLng.lng();

			this.addMarker(this.latitude, this.longitude);
		});

		this.autocomplete = new google.maps.places.Autocomplete(
			(this.$refs.address),
			{
				types: ['geocode'],
			}
		);

		this.autocomplete.addListener('place_changed', () => {
			let place = this.autocomplete.getPlace();
			let lat = place.geometry.location.lat();
			let lng = place.geometry.location.lng();

			this.address = place.formatted_address;

			this.value.latitude = lat;
			this.value.longitude = lng;

			this.addMarker(lat, lng);
		});
	}
}
</script>

<style scoped>
.google-map {
	width: 100%;
	height: 400px;
	margin-bottom: 10px;
}

#address {
	width: 100%;
	border: 1px solid rgb(186,202,214);
	border-radius: 8px;
	padding: 1px 12px;
	height: 36px;
	font-size: 16px;
	color: rgb(123,133,142);
	margin-bottom: 8px;
}
</style>
