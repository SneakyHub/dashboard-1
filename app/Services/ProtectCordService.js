const axios = require('axios');
require('dotenv').config();

class ProtectCordService {
    constructor() {
        this.apiKey = process.env.PROTECTCORD_API_KEY;
        this.apiEndpoints = [
            'https://api.protectcord.com/checkip',
            'https://api2.protectcord.com/checkip'
        ];
    }

    async checkIp(ip) {
        if (!this.apiKey) {
            throw new Error("ProtectCord API Key not set in .env.");
        }

        if (!this.validateIP(ip)) {
            throw new Error("Invalid IP address provided.");
        }

        for (let endpoint of this.apiEndpoints) {
            try {
                let response = await axios.get(`${endpoint}/${ip}`, {
                    headers: {
                        'X-API-Key': this.apiKey,
                        'Accept': 'application/json'
                    }
                });

                if (response.data) {
                    let blockConditions = [
                        response.data.is_datacenter,
                        response.data.is_proxy,
                        response.data.is_vpn,
                        response.data.is_abuser
                    ];

                    if (blockConditions.includes(true)) {
                        return {
                            block: true,
                            message: 'Access denied due to security reasons.'
                        };
                    }

                    return {
                        block: false,
                        message: null
                    };
                }
            } catch (error) {
                console.log(`Failed to check IP with ProtectCord API (endpoint: ${endpoint}):`, error.message);
            }
        }

        throw new Error("Failed to verify IP with any ProtectCord API endpoint.");
    }

    validateIP(ip) {
        return /^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ip)
            || /^[0-9a-fA-F:]+$/.test(ip); // This regex checks both IPv4 and IPv6
    }
}

module.exports = ProtectCordService;
