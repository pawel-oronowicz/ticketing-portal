import { useForm } from 'react-hook-form'
import { z } from 'zod'
import { zodResolver } from '@hookform/resolvers/zod'
import { login } from '../../api/auth'
import { useNavigate } from 'react-router'
import { useState } from "react";
import { useAuth } from "../../context/AuthContext.tsx";

const schema = z.object({
    email: z.string().email('Invalid email'),
    password: z.string(),
})

type FormData = z.infer<typeof schema>

export default function LoginPage() {
    const navigate = useNavigate()
    const [errorMessage, setErrorMessage] = useState<string | null>(null)

    const {
        register,
        handleSubmit,
        formState: { errors, isSubmitting },
    } = useForm<FormData>({
        resolver: zodResolver(schema),
    })

    const { setAuth } = useAuth()

    const onSubmit = async (data: FormData) => {
        setErrorMessage(null)
        try {
            const response = await login(data)
            setAuth(response.user, response.token)
            navigate('/')
        } catch (error) {
            setErrorMessage('Incorrect email or password. Please try again.')
        }
    }

    return (
        <div>
            <div className="flex max-w-7xl mx-auto px-6 py-6 justify-center">
                <form onSubmit={handleSubmit(onSubmit)}>
                    <fieldset className="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
                        <legend className="fieldset-legend">Log In</legend>

                        <label className="label mt-2">Email</label>
                        <input
                            {...register('email')}
                            type="email"
                            className="input"
                            placeholder="Email"
                        />
                        {errors.email && <p className="text-red-500">{errors.email.message}</p>}

                        <label className="label mt-2">Password</label>
                        <input
                            {...register('password')}
                            type="password"
                            className="input"
                            placeholder="Password"
                        />
                        {errors.password && <p className="text-red-500">{errors.password.message}</p>}

                        {errorMessage && (
                            <div className="alert alert-error mt-4">
                                {errorMessage}
                            </div>
                        )}

                        <button type="submit" disabled={isSubmitting} className="btn btn-neutral mt-4">
                            {isSubmitting ? 'Logging in...' : 'Log In'}
                        </button>
                    </fieldset>
                </form>
            </div>
        </div>
    )
}