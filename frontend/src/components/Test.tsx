import React, { useState, useEffect } from 'react';
import axios from 'axios';

const Test = () => {
    const [data, setData] = useState('');

    useEffect(() => {
        axios.get('https://127.0.0.1:8000/test')
            .then(res => {
                setData(res.data);
            })
            .catch(err => {
                console.error('Error fetching data:', err);
            });
    }, []);

    return (
        <p>
            Lucky number: {data}
        </p>
    );
};

export default Test;