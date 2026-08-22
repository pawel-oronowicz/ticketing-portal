import client from "./client.ts";

export const getSystemSettings = async() => {
    const { data } = await client.get('/system-settings')
    return data
}